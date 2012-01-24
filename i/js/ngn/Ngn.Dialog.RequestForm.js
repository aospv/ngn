Ngn.Dialog.RequestFormBase = new Class({
  Extends: Ngn.Dialog,

  options: {
    okDestroy: false,
    jsonRequest: true,
    autoSave: false,
    getFormData: function() {
      return Ngn.frm.toObj(this.form.eForm);
    },
    onSubmitSuccess: $empty
  },
  
  initialize: function(options) {
    options = options || {};
    options.ok = this.submit.bind(this);
    if (!$defined(options.submitUrl)) {
      if (!$defined(options.jsonSubmit))
        options.jsonSubmit = false;
      options.submitUrl = options.url;
    }
    this.parent(options);
    this.toggle('ok', false);
    this.addEvent('request', function(r) {
      this.formResponse(r);
    }.bind(this));
    this.iframeUpload = true;
    window.addEvent('keypress', function(e) {
      return;
      if (e.key != 'enter' || e.target.get('tag') == 'textarea') return;
      new Event(e).stop();
      this.submit();
    }.bind(this));
  },
  form: null,
  formResponse: function(r) {
    this.toggle('ok', true);
    this.loading(false);
    if (r.title) this.setTitle(r.title);
    if (r.submitTitle) this.setOkText(r.submitTitle);
    this.setMessage(r.form, false);
    this.form = new Ngn.Form(this.message.getElement('form'));
    this.form.options.dialog = this; // Важно создавать передавать объект Диалога в объект 
                                     // Формы после выполнения конструктура, иначе объект 
                                     // Даилога не будет содержать созданого объекта Формы
    
    this.addEvent('beforeClose', function() {
      //c(this.form.eForm);
      //return;
      //this.form.eForm.getElements('.elWisiwig textarea').each(function(el) {
        //c('!!!');
        //c(tinymce.EditorManager.get(el.get('id')));
        
        //tinyMCE.get(el.get('id')).destroy();
        //tinyMCE.get(el.get('id')).remove();
        //tinyMCE.execCommand('mceRemoveControl', true, el.get('id'));
        //Ngn.TinyInit.exists[el.get('id')] = true;
        //c(Ngn.TinyInit.exists);
      //});
    }.bind(this));
    
    this.resizeByCols();
    if (this.options.autoSave) {
      new Ngn.frm.Saver(this.form.eForm, {
        url: this.options.submitUrl,
        jsonRequest: true
      });
    }
    this.formInit();
    this.screen_center();
  },
  
  resizeByCols: function() {
    var cols = this.form.eForm.getElements('.type_col');
    if (!cols.length) return;
    var maxY = 0;
    var ys = [];
    var x = 0;
    for (var i=0; i<cols.length; i++) {
      ys[i] = cols[i].getSize().y;
      x += cols[i].getSize().x;
    }
    //for (var i=0; i<cols.length; i++)
      //cols[i].setStyle('height', ys.max() + 'px');
    this.dialog.setStyle('width', (x + 12) + 'px');
  },
  
  formInit: function() {},
  
  submit: function() {
    if (!this.form.validator.validate()) return;
    this._submit();
  }

});

Ngn.Dialog.Form = new Class({
  Extends: Ngn.Dialog.RequestFormBase,
  
  options: {
    onSubmit: $empty
  },
  
  _submit: function() {
    this.fireEvent('submit', this.options.getFormData.bind(this)());
    this.okClose();
  }
  
});

Ngn.Dialog.RequestForm = new Class({
  Extends: Ngn.Dialog.RequestFormBase,
  
  options: {
    autoSave: false,
    formRequest: $empty
  },
  
  formResponse: function(r) {
    this.parent(r);
    if (this.options.autoSave) {
      new Ngn.frm.Saver(this.form.eForm, {
        url: this.options.submitUrl,
        jsonRequest: true
      });
    }
  },

  _submit: function() {
    this.fireEvent('formRequest');
    this.toggle('ok', false);
    this.toggle('cancel', false);
    this.loading(true);
    this.form.iframeRequest ? this.submitIframe() : this.submitAjax()
  },
  
  submitIframe: function() {
    this.form.iframeRequest.addEvent('complete', function(r) {
      if (r && r.form) {
        // Если в ответе есть форма, значит произошла какая-то ошибка
        this.formResponse(r);
        this.toggle('cancel', true);
        return;
      }
      this.fireEvent('submitSuccess', r);
      this.okClose();
    }.bind(this));
    this.form.eForm.fireEvent('submit');
    this.form.eForm.submit();
  },
  
  submitAjax: function() {
    new Ngn.Request.JSON({
      url: this.options.submitUrl,
      onComplete: function(r) {
        if (r.form) {
          // Если в ответе есть форма, значит произошла какая-то ошибка
          this.formResponse(r);
          this.toggle('cancel', true);
          return;
        }
        this.fireEvent('submitSuccess', r);
        this.okClose();
      }.bind(this)
    }).post(this.options.getFormData.bind(this)());
  }
  
});
