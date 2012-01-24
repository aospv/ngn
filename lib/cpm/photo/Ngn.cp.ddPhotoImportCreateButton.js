Ngn.cp.ddPhotoImportCreateButton = function(dialogOptions) {
  var btnAdd = subNav.getElement('.navSubBtns').getElement('.add');
  '<a href="" class="addMany"><i></i><b>Добавить несколько</b></a>'.toDOM()[0].
  inject(btnAdd, 'after').addEvent('click', function(e) {
    new Event(e).stop();
    new (Ngn.Dialog.DdPhotoImport.getClass())(dialogOptions);
  });
  btnAdd.dispose();
};
