Ngn.DdItemsTable = new Class({
  
  Extends: Ngn.ItemsTable,
  
  options: {
    idParam: 'itemId'
  },
  
  init: function() {
    this.parent();
    this.addBtnsActions([
      ['a.editDate', function(itemId) {
        new Ngn.Dialog.RequestForm({
          url: Ngn.getPath(3)+'/json_editItemSystemDates/'+itemId
        });
      }]
    ]);
  }
  
});