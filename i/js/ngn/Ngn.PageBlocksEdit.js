Ngn.PageBlocksEdit = new Class({
  Implements: [Options],
  
  options: {
    wrapperSelector: '#blocks',
    //colSelector: '.col',
    //colBodySelector: '.blocksBody',
    handler: '.dragBox',
    controllerPath: window.location.pathname,
    disableDeleteBtn: false,
    colBodySelector: '.blocksBody'
  },

  initialize: function(options) {
    this.setOptions(options);
    this.eWrapper = document.getElement(this.options.wrapperSelector);
    this.initBlocks();
    this.initCols();
    this.initSortables();
  },
  
  cols: [],
  
  initCols: function() {
    var i = 0;
    this.eWrapper.getElements('.col').each(function(eCol) {
      if (!eCol.get('id')) return;
      var btnAdd = eCol.getElement('.add');
      var colN = parseInt(eCol.get('id').replace('col', ''));
      if (btnAdd) btnAdd.addEvent('click', function(e){
        e.preventDefault();
        new Ngn.Dialog.Queue.Request.Form({
          url: Ngn.getPath(3)+'/json_newBlock/'+colN
        }, {
          onComplete: function() {
            window.location.reload(true);
          }
        });
      });
      var eColBody = eCol.getElement(this.options.colBodySelector);
      if (!eColBody) return;
      if (!eCol.get('class').match(/blocksNotAllowed/)) {
        this.cols[i] = eColBody;
        i++;
      }
    }.bind(this));
    
    Ngn.equalItemHeights(this.cols, true);
  },
  
  initSortables: function() {
    this.sortables = new Sortables(this.cols, {
      revert: true,
      clone: true,
      handle: this.options.handler
    });
    this.sortables.addEvent('start', function(el, clone){
      clone.setStyle('z-index', 9999);
      clone.addClass('move');
    });
    this.sortables.addEvent('stop', function(el, clone){
      el.removeClass('nonActive');
      clone.removeClass('move');
    });
    // Строка отвечающая за изменение порядка
    this.orderState = this.sortables.serialize().join(',');
    this.sortables.addEvent('complete', function(el, clone){
      if (this.orderState == this.sortables.serialize().join(',')) return;
      el.addClass('loading');
      new Request({
        url: this.options.controllerPath + '/ajax_updateBlocks',
        onComplete: function() {
          el.removeClass('loading');
          this.orderState = this.sortables.serialize().join(',');
        }.bind(this)
      }).POST({
        cols: this.sortables.serialize(false, function(eBlock, index){
          if (!eBlock.get('id')) return null;
          return {
            id: eBlock.get('id').replace('block_', ''),
            colN: eBlock.getParent('.col').get('id').replace('col', '')
          }
        }.bind(this))
      });
    }.bind(this));
  },
  
  blocks: {},
  
  initBlocks: function() {
    this.eWrapper.getElements('.block').each(function(el) {
      var b = new Ngn.PageBlockEdit(el, this);
      this.blocks[b.id] = b;
    }.bind(this));
  }

});

Ngn.PageBlockEdit = new Class({

  /**
   * @var Ngn.PageBlocksEdit
   */
  initialize: function(eBlock, pbe) {
    this.pbe = pbe;
    this.eBlock = eBlock;
    this.id = this.eBlock.get('id').replace('block_', '');
    this.initEditBlock();
  },
  
  initEditBlock: function() {
    var el = this.eBlock;
    Ngn.tpls.editBlock.toDOM()[0].inject(el, 'top');
    el.getElement('.actv').dispose();
    this.eEditBlock = el.getElement('.editBlock');
    // Выравниваем editBlock по правому краю
    (function(){
      Ngn.setToTopRight(this.eEditBlock, el, [6, 0]);
    }.bind(this)).delay(100);
    
    '<div class="dragBox"></div>'.toDOM()[0].inject(el, 'top');
    
    var btnEdit = el.getElement('a[class~=sm-edit]');
    if (btnEdit) {
      btnEdit.set('href', '#');
      btnEdit.set('title', 'Редактировать блок');
      btnEdit.addEvent('click', function(e){
        e.preventDefault();
        new Ngn.Dialog.RequestForm({
          url: this.pbe.options.controllerPath + '/json_editBlock/' + this.id,
          width: 800,
          onSubmitSuccess: function() {
            this.reload();
          }.bind(this)
        }).show();
      }.bind(this));
    }
    var btnDelete = el.getElement('a[class~=sm-delete]');
    if (btnDelete) {
      if (this.pbe.options.disableDeleteBtn) {
        btnDelete.dispose();
        return;
      }
      btnDelete.addEvent('click', function(e){
        e.preventDefault();
        if (!confirm('Вы уверены?')) return;
        Ngn.loading(true);
        new Request({
          url: this.pbe.options.controllerPath + '/ajax_deleteBlock',
          onComplete: function() {
            Ngn.loading(false);
            el.destroy();
          }
        }).get({
          blockId: this.id
        });
      }.bind(this));
    }
  },
  
  reload: function() {
    this.eBlock.addClass('loading');
    new Ngn.Request({
      url: this.pbe.options.controllerPath + '/ajax_getBlock/' + this.id,
      onComplete: function(html) {
        this.eBlock.removeClass('loading');
        this.eBlock.getElement('.bcont').set('html', html);
      }.bind(this)
    }).get();
  },
  
  addEditBlockBtn: function(opts, func) {
    Ngn.smBtn(opts).toDOM()[0].inject(this.eEditBlock, 'top').addEvent('click', function(e) {
      e.preventDefault();
      func();
    });
  }

});


Ngn.pb = {};

Ngn.pb.Text = new Class({
  initialize: function(eForm) {
    Ngn.dialogs.getValues().getLast().dialog.setStyle('width', '800px');
    //Ngn.dialogs.getValues().getLast().message.setStyle('height', '600px');
    Ngn.dialogs.getValues().getLast().screen_center();
    eForm.getElement('.type_wisiwig').setStyle('padding', 0);
    eForm.getElement('.type_wisiwig > p.label').setStyle('display', 'none');
  }
});
