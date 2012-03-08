Ngn.Dialog.Auth = new Class({
  
  Extends: Ngn.Dialog,
  
  options: {
    tabsSelector: 'h2[class=tab]',
    onAuthComplete: $empty,
    reloadOnAuth: true,
    completeUrl: null,
    url: '/userReg/ajax_auth',
    selectedTab: 1,
    //footer: false,
    width: 410,
    draggable: true,
    openerType: 'default',
    fromVkEnabled: false,
    okDestroy: false
  },
  
  initialize: function(opts) {
    opts.ok = this.submit.bind(this);
    this.parent(opts);
    this.toggle('ok', false);
    if (this.options.completeUrl) this.options.reloadOnAuth = true;
    if (this.options.fromVkEnabled && Ngn.fromVk) this.options.selectedTab = 2;
    this.dialog.addClass('dialog-tabs');
  },
  
  submit: function() {
    this.loading(true);
    var formId = this.tabs.tabs[this.tabs.selected].name;
    this.forms[formId].submitAjax();
  },
  
  vkInitialized: false,
  
  vkLogin: function(vkResult) {
    return vkResult.first_name + ' ' + vkResult.last_name;
  },
  
  vkInit: function() {
    var eVkAuth = $('vkAuth');
    if (!eVkAuth) return;
    if (this.vkInitialized) return;
    if (!Ngn.vkApiId) throw new  Error('VK API ID does not defined in config');
    var eVkApiTransport = new Element('div', {id: 'vk_api_transport'}).inject(eVkAuth);
    var vkResultReceived = false;
    window.vkAsyncInit = function() {
      VK.init({apiId: Ngn.vkApiId});
      VK.Widgets.Auth('vkAuth', {
        width: 398,
        onAuth: function(vkResult) {
          if (vkResultReceived) return;
          vkResultReceived = true;
          new Ngn.Request({
            url: '/c/vkAuth/ajax_exists',
            onComplete: function(r) {
              this.vkRequest(vkResult, !(r == 'success'))
            }.bind(this)
          }).post({login: this.vkLogin(vkResult)});
        }.bind(this)
      });
      this.vkInitialized = true;
    }.bind(this);
    (function() {
      var eScript = 
      new Element('script', {
        type: 'text/javascript',
        src: 'http://vkontakte.ru/js/api/openapi.js',
        async: true
      }).inject(eVkApiTransport);
    }).delay(0);
  },
  
  urlRequest: function(_response) {
    this.toggle('ok', true);
    this.parent(_response);
    this.tabs = new Ngn.Tabs(this.message, {
      selector: this.options.tabsSelector,
      show: this.options.selectedTab,
      stopClickEvent: true,
      onSelect: function(toggle, container, index) {
        if (container.get('id') == 'vkAuth') {
          this.vkInit();
          this.footer.setStyle('display', 'none');
        } else {
          this.footer.setStyle('display', 'block');
        }
      }.bind(this)
    });
    this.tabs.eMenu.inject(this.titleText);
    this.message.getElements('form').each(function(eForm, n) {
      this.initForm(eForm);
    }.bind(this));
    if (Ngn.Dialog.Auth.requestActions.length)
      for (var i=0; i<Ngn.Dialog.Auth.requestActions.length; i++)
        Ngn.Dialog.Auth.requestActions[i].bind(this)();
  },
  
  vkRequest: function(vkResult, create) {
    var data = {
      login: this.vkLogin(vkResult),
      uid: vkResult.uid,
      hash: vkResult.hash
    };
    if (create) {
      // Регистрация нового
      data.pass = prompt(
        'Введите пароль для вашего профиля на сайте ' + Ngn.siteTitle, '');
      if (vkResult.photo) data.image = vkResult.photo;
    }
    new Ngn.Request({
      url: '/c/vkAuth' + (create ? '/ajax_reg' : ''),
      onComplete: function(r) {
        if (r == 'success') {
          this.authComplete();
        } else {
          alert('Ошибка авторизации');
        }
      }.bind(this)
    }).post(data);
  },
  
  forms: {},
  
  initForm: function(eForm) {
    var form = new Ngn.Form(eForm);
    this.forms[eForm.get('id')] = form;
    form.validator.options.scrollToErrorsOnSubmit = false;
    form.addEvent('complete', function(r) {
      this.loading(false);
      if (r.success) {
        eForm.get('id') == 'frmAuth' ?
          this.submitSuccessAuth(r) :
          this.submitSuccessUserReg(r);
      } else {
        if (!r.form) throw new Ngn.EmptyError('r.form');
        var par = eForm.getParent();
        eForm.dispose();
        par.set('html', r.form);
        this.initForm(par.getElement('form'));
      }
    }.bind(this));
  },
  
  submitSuccessAuth: function(r) {
    this.authComplete();
  },
  
  submitSuccessUserReg: function(r) {
    if (r.activation) {
      this.close();
      this.fireEvent('activation', r.activation);
    } else if (r.authorized) {
      this.authComplete();
    }
  },
  
  authComplete: function() {
    this.fireEvent('authComplete', this);
    this.close();
    if (this.options.reloadOnAuth) {
      new Ngn.Dialog.Loader.Simple({
        title: 'Подождите...',
        hasFaviconTimer: false
      });
      this.options.completeUrl ?
        window.location.assign(this.options.completeUrl) :
        window.location.reload(true);
    } else {
      if (Ngn.site.top.auth.reload) Ngn.site.top.auth.reload();
    }
  }
  
});
Ngn.Dialog.Auth.requestActions = [];