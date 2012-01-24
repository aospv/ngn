window.addEvent('domready', function() {
  Ngn.site.ddPhotoImportCreateButton({
    strName: Ngn.site.page.strName,
    url: '/c/photoalbumSlaveImport/'+Ngn.site.page.id+'/'+Ngn.getParam(1).split('.')[2]
  });
});
