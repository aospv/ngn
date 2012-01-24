Ngn.Dialog.DdPhotoImport = new Class({
  Extends: Ngn.Dialog,
  
  options: {
    bindBuildMessageFunction: true,
    width: 500,
    maxHeight: 500,
    dialogClass: 'dialog dialog-addPhotos',
    title: 'Добавление фотографий',
    okText: 'Добавить фотографии',
    createParams: {}
    // strName
    // url
  },

  initialize: function(opts) {
    this.parent($merge(opts, {
      ok: this.okAction.bind(this)
    }));
  }

});

Ngn.Dialog.DdPhotoImport.getClass = function() {
  return (Browser.Plugins.Flash.version && 0) ?
    Ngn.Dialog.DdPhotoImport.FancyUpload :
    Ngn.Dialog.DdPhotoImport.Simple;
};

Ngn.Dialog.DdPhotoImport.Simple = new Class({
  Extends: Ngn.Dialog.RequestForm,
  
  options: {
    width: 400,
    onSubmitSuccess: function() {
      window.location.reload();
    }
  }

});

Ngn.Dialog.DdPhotoImport.FancyUpload = new Class({
  Extends: Ngn.Dialog.DdPhotoImport,
  
  options: {
    onClose: function() {
      this.attach.uploader.box.dispose();
    }
  },
  
  initialize: function(options) {
    options.url += '/json_fancyUpload';
    this.parent(options);
  },
  
  buildMessage: function() {
    var eDiv = new Element('div', {'class': 'fu-item'});
    var eBtn = Ngn.btn('Выбрать фотографии', 'btn2');
    var eList = new Element('ul', {'class': 'fu-list'}).inject(eDiv);
    this.eForm = '<div class="apeform"><div class="element"><span class="checkbox"><input type="checkbox" value="1" id="filenameAsTitle" name="filenameAsTitle"><label for="filenameAsTitle">Использовать имена файлов в качестве названий</label></span></div><br></div>'.toDOM()[0].inject(eDiv);
    eBtn.inject(eDiv);
    eList.inject(eDiv);
    this.attach = new Ngn.UploadAttach(eList, eBtn, {
      url: Ngn.getAttacheTempUrl(this.options.strName),
      multiple: true
    });
    return eDiv;
  },
  
  okAction: function() {
    new Ngn.Request.JSON({
      url:
//        url.toURI().setData($merge(
//          this.options.createParams,
//          Ngn.frm.toObj(this.eForm)
//        ), true).toStrng(),
        this.options.url.toURI().setData(Ngn.frm.toObj(this.eForm), true).toStrng(),
      onComplete: function(d) {
        if (d.success) window.location.reload();
      }
    }).send();
  }
  
});
