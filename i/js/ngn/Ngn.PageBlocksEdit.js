Ngn.PageBlocksEdit = new Class({

  initialize: function(colsN) {
    if (!colsN) alert('colsN not defined');
    this.colsN = colsN;
    this.eBlocks = $('blocks');
    
    var elements = [];
    var ii = 0;
    this.eBlocks.getElements('.col').each(function(eCol) {
      var btnAdd = eCol.getElement('.add');
      var colN = parseInt(eCol.get('id').replace('col', ''));
      if (btnAdd) btnAdd.addEvent('click', function(e){
        new Event(e).stop();
        new Ngn.Dialog.Queue.Request.Form({
          url: Ngn.getPath(3)+'/json_newBlock/'+colN
        }, {
          onComplete: function() {
            window.location.reload(true);
          }
        });
      });
      
      if (!eCol.get('class').match(/blocksNotAllowed/)) {
        elements[ii] = eCol.getElement('.blocksBody');
        ii++;
      }
    });
    this.oST = new Sortables(elements, {
      revert: true,
      clone: true,
      handle: '.block'
    });
    this.oST.addEvent('start', function(el, clone){
      clone.setStyle('z-index', 9999);
      clone.addClass('move');
    });
    this.oST.addEvent('stop', function(el, clone){
      clone.removeClass('move');
    });
    
    // Строка отвечающая за изменение порядка
    this.orderState = this.oST.serialize().join(',');
    
    this.oST.addEvent('complete', function(el, clone){
      if (this.orderState == this.oST.serialize().join(',')) return;
      new Request({
        url: window.location.pathname + '?a=ajax_updateBlocks',
        onComplete: function() {
          this.orderState = this.oST.serialize().join(',');
        }.bind(this)
      }).POST({
        'cols' : this.oST.serialize(false, function(element, index){
          if (!element.get('id')) return null;
          return {
            'id': element.get('id').replace('block_', ''),
            'colN': element.getParent().getParent().get('id').replace('col', '')
          }
        })
      });    
    }.bind(this));

    this.eBlocks.getElements('.block').each(function(el) {
      // Выравниваем editBlock по правому краю
      (function(){
        Ngn.setToTopRight(el.getElement('.editBlock'), el, [2, 0]);
      }).delay(100);
      
      var blockId = el.get('id').replace('block_', '');

      var btnEdit = el.getElement('a[class~=sm-edit]');
      if (btnEdit) {
        btnEdit.addEvent('click', function(e){
          new Event(e).stop();
          new Ngn.Dialog.RequestForm({
            url: Ngn.getPath() + '?a=json_editBlock&id=' + blockId,
            width: 800
          }).show();
        });
      }
      
      var btnDelete = el.getElement('a[class~=sm-delete]');
      if (btnDelete) {
        btnDelete.addEvent('click', function(e){
          if (!confirm('Вы уверены?')) return false;
          Ngn.loading(true);
          new Request({
            url: window.location.pathname,
            onComplete: function() {
              Ngn.loading(false);
              el.destroy();
            }      
          }).POST({
            action: 'ajax_deleteBlock',
            blockId: blockId
          });
          return false;
        });
      }
    }.bind(this));
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
