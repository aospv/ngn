window.addEvent('domready', function() {
  Ngn.cp.ddPhotoImportCreateButton({
    strName: Ngn.site.page.strName,
    url: Ngn.getPath(1) + '/photoImport/' + Ngn.site.page.id + '/' + '?a=json_upload'
  });
});
