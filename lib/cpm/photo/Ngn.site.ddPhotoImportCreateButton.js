Ngn.site.ddPhotoImportCreateButton = function(dialogOptions) {
  var btnCreate = $('btnCreate');
  if (!btnCreate) return;
  if (btnCreate.hasClass('auth')) return;
  var btnAddMany = '<a href="#" class="btn"><span>Добавить фото</span></a>'.toDOM()[0].
    inject(btnCreate, 'after');
  btnCreate.dispose();
  btnAddMany.addEvent('click', function(e) {
    e.preventDefault();
    new (Ngn.Dialog.DdPhotoImport.getClass())(dialogOptions);
  });
};