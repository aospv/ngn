Ngn.regNamespace('Ngn.site.userGroup', true);

Ngn.site.userGroup.InfoDialog = new Class({
  Extends: Ngn.Dialog.RequestForm,
  Implements: [Ngn.BlockEditDialog.Static],
  
  options: {
    onSubmitSuccess: function() {
      var eBlock = document.body.getElement('.pbt_userGroupInfo');
      var eBlockCont = eBlock.getElement('.bcont');
    }
  }
});

Ngn.site.userGroup.EditTreeTagsDialog = new Class({
  Extends: Ngn.Dialog,
  Implements: [Ngn.BlockEditDialog.Dynamic],
  
  options: {
    title: 'Редактирование рубрик',
    footer: false,
    width: 300,
    height: 250,
    bindBuildMessageFunction: true,
    dialogClass: 'dialog treeTagsDialog'
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
    var eTreeContainer = this.message.getElement('.treeContainer');
    var eTreeMenu = this.message.getElement('.treeMenu');
    var tree = new Ngn.TreeEditTags(
      eTreeContainer,
      this.options.data.groupId,
      {
        actionUrl: '/userGroup',
        buttons: eTreeMenu,
        onUpdate: function() {
          this.updateBlock();
        }.bind(this)
      }
    ).init();
    tree.addEvent('dataLoad', function() {
      eTreeContainer.setStyle('height', (this.message.getSize().y - eTreeMenu.getSize().y) + 'px');
      tree.toggleAll(true);
    }.bind(this));
  }
  
});