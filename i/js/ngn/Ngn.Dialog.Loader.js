Ngn.Dialog.Loader = new Class({
  Extends: Ngn.Dialog,
  
  options: {
    bindBuildMessageFunction: true,
    ok: false,
    hasFaviconTimer: true // при редиректе, после включения DialogLoader'а FaviconTimer необходимо отключить
  },
  
  initialize: function(options) {
    this.parent(options);
  },
  start: function() {
    if (this.options.hasFaviconTimer) Ngn.faviconTimer.start();
  },
  
  stop: function() {
    if (this.options.hasFaviconTimer) Ngn.faviconTimer.stop();
  },
  
  close: function() {
    this.stop();
    this.parent();
  },
  
  buildMessage: function() {
    return '<div class="dialog-progress"></div>';
  }
  
});

Ngn.Dialog.Loader.Simple = new Class({
  Extends: Ngn.Dialog.Loader,

  options: {
    //cancel: false,
    titleClose: false,
    footer: false,
    messageBoxClass: 'dummy',
    titleBarClass: 'dialog-loader-title',
    titleTextClass: 'dummy',
    messageAreaClass: 'dummy',
    bindBuildMessageFunction: true
  }
  
});

Ngn.Dialog.Loader.Advanced = new Class({
  Extends: Ngn.Dialog.Loader,

  options: {
    messageAreaClass: 'dialog-message dialog-message-loader',
    onContinue: $empty(),
    noPadding: false
  },
  
  init: function() {
    this.eProgress = this.message.getElement('.dialog-progress');
    this.stop();
  },
  
  buildMessage: function() {
    return '<div class="message-text"></div><div class="dialog-progress"></div>';
  },
  
  start: function() {
    this.eProgress.removeClass('stopped');
    this.parent();
  },
  
  stop: function() {
    this.eProgress.addClass('stopped');
    this.parent();
  }
  
});

Ngn.Dialog.Loader.Request = new Class({
  Extends: Ngn.Dialog.Loader.Simple,
  
  options: {
    loaderUrl: null,
    onLoaderComplete: $empty,
    titleClose: false,
    footer: false
  },
  
  initialize: function(options) {
    this.parent(options);
    new Request({
      url: this.options.loaderUrl,
      onComplete: function(r) {
        this.okClose();
        this.options.onLoaderComplete(r)
      }.bind(this)
    }).send();
  }
  
});

Ngn.Dialog.Loader_ = new Class({

  Implements: [Options, Events],
  
  options: {
    hasCloseBtn: false,
    onClose: $empty(),
    onContinue: $empty(),
    btnCloseTitle: 'Остановить',
    btnClose2Title: 'Закрыть',
    btnContinueTitle: 'Продолжить',
    hasFaviconTimer: true // при редиректе, после включения DialogLoader'а FaviconTimer необходимо отключить
  },
  
  eTitle: null,
  btnClose: null,
  btnClose2: null,
  btnContinue: null,
  initDocumentTitle: null,
  closed: false,
  
  initialize: function(title, options) {
    this.setOptions(options);
    this.dialog = new Ngn.Dialog({autoShow: false}).toggleShade(true);
    this.initDocumentTitle = document.title;
    this.eLoader = new Element('div', {
      'class': 'shade-loader'
    }).inject(document.body);
    this.eTitle = new Element('span', {
      'class': 'shade-loader-title',
      'html': title ? title : ''
    }).inject(this.eLoader);    
    this.eProgress = new Element('div', {
      'class': 'shade-loader-progress'
    }).inject(this.eLoader);
    this.initBtns();
    this.initTitle = title;
    this.setTitle(title);
    if (this.options.hasFaviconTimer) Ngn.faviconTimer.start();
    window.addEvent('resize', this.setToCenter.bind(this));
  },
  
  initBtns: function() {
    if (this.options.hasCloseBtn) {
      this.btnClose = Ngn.btn(this.options.btnCloseTitle, null, {
        'events': {
          'click': function() {
            this.close();
            return false;
          }.bind(this)
        }
      }).inject(this.eLoader);
    }
    
    this.btnContinue = Ngn.btn(this.options.btnContinueTitle, null, {
      'styles': {
        'margin-right': '10px',
        'display': 'none'
      },
      'events': {
        'click': function() {
          this._continue();
          return false;
        }.bind(this)
      }
    }).inject(this.eLoader);
     this.btnClose2 = Ngn.btn(this.options.btnClose2Title, null, {
      'styles': {
        'display': 'none'
      },
      'events': {
        'click': function() {
          this.close();
          return false;
        }.bind(this)
      }
    }).inject(this.eLoader);
  },
  
  setToCenter: function() {
    Ngn.setToCenter(this.eLoader, window);
    //Ngn.setToCenterBlock(this.btnClose, this.eLoader);
  },
  
  setTitle: function(title) {
    if (this.closed) return;
    this.eTitle.set('html', title);
    document.title = title + ' - ' + this.initDocumentTitle;
    this.setToCenter();
  },
  
  // вызывается по кнопке из интерфейса
  close: function() {
    this.closed = true;
    document.title = this.initDocumentTitle;
    this.dialog.close();
    this.eLoader.dispose();
    if (this.options.hasFaviconTimer) Ngn.faviconTimer.stop();
    this.fireEvent('close', this);
  },
  
  _continue: function() {
    this.start();
    this.fireEvent('continue', this);
  },

  start: function() {
    if (this.options.hasCloseBtn) this.btnClose.setStyle('display', 'inline-block');
    this.btnClose2.setStyle('display', 'none');
    this.btnContinue.setStyle('display', 'none');
    this.eLoader.removeClass('stopped');
    this.setTitle(this.initTitle);
  },
  
  stop: function() {
    if (this.options.hasCloseBtn) this.btnClose.setStyle('display', 'none');
    this.btnClose2.setStyle('display', 'inline-block');
    this.btnContinue.setStyle('display', 'inline-block');
    this.eLoader.addClass('stopped');
    this.setToCenter();
  }
  
});
