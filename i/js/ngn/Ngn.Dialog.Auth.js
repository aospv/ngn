Ngn.Dialog.Auth = new Class({
  
  Extends: Ngn.Dialog,
  
  options: {
    tabsSelector: 'h2[class=tab]',
    onAuthComplete: $empty,
    reloadOnAuth: false,
    completeUrl: null,
    url: '/c/auth',
    selectedTab: 1,
    footer: false,
    width: 410,
    draggable: true,
    openerType: 'default',
    fromVkEnabled: false
  },
  
  initialize: function(opts) {
    this.parent(opts);
    if (this.options.completeUrl) this.options.reloadOnAuth = true;
    if (this.options.fromVkEnabled && Ngn.fromVk) this.options.selectedTab = 2;
    this.dialog.addClass('dialog-tabs');
  },
  
  vkInitialized: false,
  
  vkLogin: function(vkResult) {
    return vkResult.first_name + ' ' + vkResult.last_name;
  },
  
  vkInit: function() {
    if (this.vkInitialized) return;
    if (!Ngn.vkApiId) throw new  Error('VK API ID does not defined in config');
    var eVkApiTransport = new Element('div', {id: 'vk_api_transport'}).inject($('vkAuth'));
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
    this.parent(_response);
    var tabs = new SimpleTabs(this.message, {
      selector: this.options.tabsSelector,
      show: this.options.selectedTab,
      stopClickEvent: true,
      onSelect: function(toggle, container, index) {
        if (container.get('id') == 'vkAuth') this.vkInit();
      }.bind(this)
    });
    tabs.menu.inject(this.titleText);
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
  
  initForm: function(eForm) {
    var form = new Ngn.Form(eForm);
    form.validator.options.scrollToErrorsOnSubmit = false;
    var postLogin;
    var postPass;
    eForm.set('send', {
      onComplete: function(response) {
        if (response == 'success') {
          this.authComplete();
        } else {
          var par = eForm.getParent();
          eForm.dispose();
          par.set('html', response);
          this.initForm(par.getElement('form'));
        }
      }.bind(this)
    });
    eForm.addEvent('submit', function(e) {
      new Event(e).stop();
      if (!form.validator.validate()) return;
      eForm.getElement('input[type=submit]').set('disabled', true);
      eForm.send();
    }.bind(this));
    //this.fireEvent('request');
  },
  
  authComplete: function() {
    this.fireEvent('authComplete', this);
    this.close();
    if (this.options.reloadOnAuth) {
      new Ngn.Dialog.Loader.Simple('Подождите...', {
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