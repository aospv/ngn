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
  Extends: Ngn.EditTreeTagsDialog,
  
  options: {
    actionUrl: '/userGroup'
  }

});

