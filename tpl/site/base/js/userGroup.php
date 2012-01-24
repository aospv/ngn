Ngn.site.top.briefcase.btns.push(['add', 'Создать сообщество', function() {
  new Ngn.Dialog.RequestForm({
    url: '/userGroup/json_new',
    onSubmitSuccess: function() {
      //window.location.reload(true);
    }
  });
}]);

<? if ($d['action'] != 'list' or $d['oController']->userGroup['userId'] != Auth::get('id')) return; ?>

window.addEvent('domready', function() {
  var eBlock = document.getElement('.pbt_tags');
  var eBlockCont = eBlock.getElement('.bcont');
  var eBar = Ngn.smBtns([{
    name: 'edit',
    title: 'Редактировать'
  }]).addClass('moderBar').inject(eBlock, 'top').setStyle('float', 'right');
  Ngn.addBtnAction('.sm-edit', function() {
    new Ngn.site.userGroup.EditTreeTagsDialog({
      blockId: eBlock.get('id').replace('block_', ''),
      data: JSON.decode(eBlockCont.getElement('.data').get('html'))
    });
  }, eBar);
});
