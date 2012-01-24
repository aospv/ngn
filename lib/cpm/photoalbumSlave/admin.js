window.addEvent('domready', function() {
  Ngn.cp.ddPhotoImportCreateButton({
    strName: Ngn.getElementJson($('pageControllerSettings')).strName,
    url: Ngn.getPath(1) + '/photoalbumSlaveImport/' + Ngn.getParam(2) + '/' + 
      Ngn.getParam(4).split('.')[2] + '?a=json_upload'
  });
});