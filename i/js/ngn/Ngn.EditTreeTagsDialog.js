Ngn.Dialog.TreeResizeble = new Class({
  Extends: Ngn.Dialog.Resizeble,
  
  getResizebleEl: function() {
    return this.dialog.eTreeContainer;
  }
  
});

Ngn.EditTreeTagsDialog = new Class({
  Extends: Ngn.Dialog,
  Implements: [Ngn.BlockEditDialog.Dynamic],
  
  options: {
    id: 'editTags',
    title: 'Редактирование рубрик',
    footer: false,
    width: 300,
    //height: 250,
    bindBuildMessageFunction: true,
    dialogClass: 'dialog treeTagsDialog',
    actionUrl: '/admin/tags',
    resizeble: Ngn.Dialog.TreeResizeble
    //data: {
    //  groupId: null
    //}
  },
  
  buildMessage: function() {
    return '\
<div>\
  <div class="treeMenu iconsSet">\
    <small>\
      <a href="#" class="add dgray"><i></i>Создать</a>\
      <a href="#" class="rename dgray"><i></i>Переименовать</a>\
      <a href="#" class="delete dgray"><i></i>Удалить</a>\
    </small>\
    <div class="clear"><!-- --></div>\
  </div>\
  <div class="treeContainer"></div></div>\
</div>\
    '.toDOM()[0];
  },
  
  init: function() {
    this.eTreeContainer = this.message.getElement('.treeContainer');
    this.eTreeMenu = this.message.getElement('.treeMenu');
    var tree = new Ngn.TreeEditTags(
      this.eTreeContainer,
      this.options.data.groupId,
      {
        actionUrl: this.options.actionUrl,
        buttons: this.eTreeMenu,
        onUpdate: function() {
          this.updateBlock();
        }.bind(this)
      }
    ).init();
    tree.addEvent('dataLoad', function() {
      this.eTreeContainer.setStyle('height',
        (this.message.getSize().y - this.eTreeMenu.getSize().y) + 'px');
      this.initReduceHeight(true);
      tree.toggleAll(true);
    }.bind(this));
  }
  
});