/*
if (ddItems) ddItems.getElements('.editBlock').each(function(el) {
    
    el.setStyle('width', el.getSize().x+'px');
    el.setStyle('position', 'absolute');
    var par = el.getParent();
    par.setStyle('position', 'relative');
    Ngn.setToTopRight(el, par, [10, 0]);
  });
*/
Ngn.site.DdItems = new Class({
  Implements: [Options],
  
  options: {
    curUserId: null,
    isAdmin: false,
    editPath: null,
    useUserId: false,
    sortables: false
  },
  
  initialize: function(esItems, options) {
    this.setOptions(options);
    this.esItems = esItems;
    if (!this.options.editPath) {
      this.options.editPath = Ngn.getPath(1);
    } else {
      this.options.useUserId = true;
    }
    this.editBlockTpl = Ngn.tpls.editBlock;
    this.esItems.each(function(eItem) {
      if (this.options.isAdmin || eItem.get('data-userId') == this.options.curUserId)
        this.addEditBlock(eItem);
    }.bind(this));
    this.initSortables();
  },
  
  initSortables: function() {
    if (!this.options.sortables) return;
    this.sortables = new Sortables(this.esItems.getParent(), {
      revert: true,
      clone: true,
      handle: '.dragBox'
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
      //if (this.orderState == this.sortables.serialize().join(',')) return;
      el.addClass('loading');
      new Request({
        url: Ngn.getPath(1) + '/ajax_reorder',
        onComplete: function() {
          el.removeClass('loading');
          this.orderState = this.sortables.serialize().join(',');
        }.bind(this)
      }).POST({
        ids: this.sortables.serialize(false, function(eItem, index){
          if (eItem.hasClass('move')) return;
          return eItem.get('data-id');
        }.bind(this))
      });
    }.bind(this));
  },
  
  addEditBlock: function(eItem) {
    var eCont = eItem.getElement('.itemBody');
    var idParam = this.options.useUserId ? 'data-userId' : 'data-id';
    var eEditBlock =
      Ngn.strReplace('{path}', this.options.editPath,
      Ngn.strReplace('{id}', eItem.get(idParam),
        this.editBlockTpl)).toDOM()[0];
    eEditBlock.inject(eCont);
    if (this.options.sortables)
      '<div class="dragBox"></div>'.toDOM()[0].inject(eCont, 'top');
    this.initSortables(eItem);
    eCont.setStyle('position', 'relative');
    Ngn.setToTopRight(eEditBlock, eCont, [10, 3]);
    eEditBlock.setStyle('width', eEditBlock.getSize().x+'px');
    eEditBlock.setStyle('position', 'absolute');
    var btnEdit = eEditBlock.getElement('.sm-edit');
    btnEdit.removeEvent('click');
    btnEdit.addEvent('click', function(e) {
      e.preventDefault();
      new Ngn.Dialog.RequestForm({
        title: false,
        messageAreaClass: 'dialog-message dialog-dd',
        width: 700,
        url: btnEdit.get('href').replace('edit', 'json_edit'),
        onSubmitSuccess: function() {
          window.location.reload(true);
        }
      });
    });
    var btnDelete = eEditBlock.getElement('.sm-delete');
    btnDelete.addEvent('click', function(e) {
      e.preventDefault();
      if (!confirm('Вы уверены?')) return;
      var eItem = eEditBlock.getParent('.item');
      var fx = new Fx.Morph(eItem, { duration: 'short', link: 'cancel' });
      fx.start({'opacity': [1, 0.4]});
      new Ngn.Request({
        url: btnDelete.get('href').replace('delete', 'ajax_delete'),
        onComplete: function() {
          fx.start({'opacity': [0.4, 0]});
          fx.addEvent('complete', function() {
            eItem.dispose();
          });
        }
      }).send();
    });
    
    var active = !eItem.hasClass('nonActive');
    var btnActivate = eEditBlock.getElement('.sm-activate');
    btnActivate.removeClass('sm-activate').addClass('sm-'+(active ? 'deactivate' : 'activate'));
    btnActivate.set('title', active ? 'Скрыть' : 'Показать');
    if (btnActivate) {
      btnActivate.addEvent('click', function(e) {
        e.preventDefault();
        eItem.addClass('loading');
        new Ngn.Request({
          url: btnActivate.get('href').replace(
            'activate',
            active ? 'ajax_deactivate' : 'ajax_activate'
          ),
          onComplete: function() {
            eItem.removeClass('loading');
            if (active) {
              active = false;
              eItem.addClass('nonActive');
              btnActivate.removeClass('sm-deactivate').addClass('sm-activate');
              btnActivate.set('title', 'Показать');
            } else {
              active = true;
              eItem.removeClass('nonActive');
              btnActivate.removeClass('sm-activate').addClass('sm-deactivate');
              btnActivate.set('title', 'Скрыть');
            }
          }
        }).send();
      });
    }
    
  }
  
});

Ngn.site.DdItems.next = function(esItems, curId) {
  return Ngn.site.DdItems._next(esItems, curId, true);
};

Ngn.site.DdItems.prev = function(esItems, curId) {
  return Ngn.site.DdItems._next(esItems, curId, false);
};

Ngn.site.DdItems._next = function(esItems, curId, next) {
  for (var i=0; i<esItems.length; i++)
    if (esItems[i].get('data-id') == curId)
      if (next)
        return esItems[i+1] ? esItems[i+1] : esItems[0];
      else
        return esItems[i-1] ? esItems[i-1] : esItems[esItems.length-1];
};

Ngn.site.DdItems.EquailSizes = new Class({
  
  initialize: function(selector) {
    this.selector = selector ? selector : '.ddil_tile .ddItems .item';
    this.esItems = $$(this.selector);
    this.init();
  },
  
  init: function() {
    if (!this.esItems.length) return;
    this.cacheId = Ngn.getPath();
    var esImg = $$(this.selector+' img');
    var imgLinks = [];
    for (var i=0; i<esImg.length; i++) imgLinks.push(esImg[i].get('src'));
    new Asset.images(imgLinks, {
      onComplete: function(){
        Ngn.equalItemHeights(this.esItems);
      }.bind(this)
    });
  }
  
});
