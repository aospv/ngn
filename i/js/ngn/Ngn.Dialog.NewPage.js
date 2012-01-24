Ngn.Dialog.NewPageBase = new Class({
  Extends: Ngn.Dialog.RequestForm,
  
  getOpt: function() {
    return {
      getFormData: function() {
        var data = Ngn.frm.toObj(this.form.eForm);
        data.parentId = this.options.pageId;
        return data;
      }
    };
  }
  
});


Ngn.Dialog.NewPage = new Class({
  Extends: Ngn.Dialog.NewPageBase,

  initialize: function(_opts) {
    this.parent($merge(_opts, this.getOpt(), {
      title: 'Ручное создание нового раздела',
      url: Ngn.getPath(2) + '/' + _opts.pageId + '/json_newPage'
    }));
  },
  
  formResponse: function(r) {
    this.parent(r);
    Ngn.frm.initTranslateField('titlei', 'namei');
    this.message.getElement('input[name=title]').focus();
  }
  
});

Ngn.Dialog.NewModulePage = new Class({
  Extends: Ngn.Dialog.NewPageBase,

  initialize: function(_opts) {
    this.parent($merge(_opts, this.getOpt(), {
      title: 'Создание нового раздела',
      url: Ngn.getPath(2) + '/' + _opts.pageId + '/json_newModulePage'
    }));
  },
  
  formResponse: function(r) {
    this.parent(r);
    Ngn.frm.initTranslateField('titlei', 'namei');
    Ngn.frm.initCopySelectValue('modulei', 'namei');
    Ngn.frm.initCopySelectTitle('modulei', 'titlei');
    this.message.getElement('input[name=title]').focus();
  }
  
});
