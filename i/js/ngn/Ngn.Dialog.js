Ngn.dialogs = new Hash({});

Ngn.Dialog = new Class({
  Implements: [Ngn.RequiredOptions, Events],
  options: {
    id: 'dlg',
    autoShow: true,
    buttons: null,
    cancel: null,
    cancelClass: 'cancel',
    cancelText: 'Отмена',
    cancelDestroy: true,
    callback: null,
    center: true,
    dialogClass: 'dialog',
    draggable: true,
    fxOptions: {},
    footer: null,
    footerClass: 'dialog-footer iconsSet',
    force: true,
    height: 'auto',
    message: null,
    messageAreaClass: 'dialog-message',
    messageBoxClass: 'mid-float-box',
    noTitleClass: 'mav-no-title',
    noFooterClass: 'mav-no-footer',
    ok: null,
    okClass: 'ok',
    okText: 'OK',
    okDestroy: true,
    parent: null,
    shadeClass: 'dialog-shade',
    styles: {},
    title: '',
    titleBarClass: 'dialog-title',
    titleClose: true,
    titleCloseClass: 'icon-button md-closer',
    titleCloseTitle: 'Закрыть окно',
    titleTextClass: 'md-title-text',
    url: null,
    useFx: !Browser.Engine.trident,
    //'useFx: false,
    width: 500,
    bindBuildMessageFunction: false,
    noPadding: true,
    setMessageDelay: null,
    forceShadeClose: false,
    jsonRequest: false,
    reduceHeight: false,
    baseZIndex: 300,
    //'maxHeight: null,
    //resizeble: Ngn.Dialog.Resizeble,

    onComplete: $empty(),
    onClose: $empty(),
    onOkClose: $empty(),
    onCancelClose: $empty(),
    onHide: $empty(),
    onRequest: $empty(),
    onShow: $empty()
  },

  delayedShow: false,
  closed: false,
  dialog: null,
  drag: null,
  footer: null,
  fx: null,
  grabbed: null,
  message: null,
  parent: null,
  request: null,
  titlebar: null,
  isOkClose: false,
  btns: {},
  
  initialize: function(_opts) {
    this.setOptions(_opts);
    
    if (this.options.noPadding)
      this.options.messageAreaClass += ' dialog-nopadding';
    
    if (this.options.reduceHeight)
      this.options.messageAreaClass += ' dialog-scroll';
    
    if ($(this.options.id + '_dialog')) { return null; }

    if (this.options.bindBuildMessageFunction)
      this.options.message = this.buildMessage.bind(this, this.options.message);
    
    this.request = new (this.options.jsonRequest ? Ngn.Request.JSON : Ngn.Request)({
      onSuccess: this.urlRequest.bind(this),
      onFailure: this.errorMessage.bind(this)
    });
    
    this.dialogId = this.options.id + '_dialog';
    this.dialogN = Ngn.dialogs.length() + 1;
    Ngn.dialogs[this.dialogId] = this;
    
    this.parentElement = $((this.options.parent || document.body));
    
    var dialog_styles = $merge({
      'display': 'none',
      'width': this.options.width.toInt()+'px',
      'z-index': this.options.baseZIndex + (this.dialogN * 2)
    }, this.options.styles);

    this.dialog = new Element('div', {
      'id': this.dialogId, 
      'class': this.options.dialogClass,
      //'opacity': (this.options.useFx ? 0 : 1),
      'styles': dialog_styles
    }).inject(this.parentElement);

    this.fx = this.options.useFx ? new Fx.Tween(this.dialog, $merge({
      duration: '500'
    }, this.options.fxOptions)) : null;
    if (this.fx) this.fx.set('opacity', 0);
    
    //dialog-message
    //if (this.options.maxHeight)
      //this.message.setStyle('max-height', this.options.maxHeight+'px');
      //this.options.maxHeight;
    
    // dialog box sections and borders
    this.eMessage = new Element('div', {
      'class': this.options.messageBoxClass
    }).inject(this.dialog);
    
    // dialog box title
    if (this.options.title !== false) {
      this.titlebar = new Element('div', {
        'id': this.options.id + '_title',
        'class': this.options.titleBarClass
      }).inject(this.eMessage);
      
      this.titleText = new Element('span', {'class':this.options.titleTextClass, 'html': this.options.title}).inject(this.titlebar);

      if (this.options.titleClose != false) {
        this.btnClose = Ngn.opacityBtn(
          new Element('span', {
            'id':this.options.id + '_closer',
            'class': this.options.titleCloseClass,
            'title': this.options.titleCloseTitle
          }).inject(this.titlebar).addEvent('click', this.close.bind(this))
        );
      }
    }

    // dialog box message
    this.message = new Element('div', {
      'id': this.options.id + '_message', 
      'class': this.options.messageAreaClass + (this.options.title === false ? ' ' + this.options.noTitleClass : '') + (this.options.footer === false ? ' ' + this.options.noFooterClass : '')
    }).inject(this.eMessage).setStyle('height', (this.options.height=='auto'?'auto':this.options.height.toInt()+'px'));
    
    if ($defined(this.options.url)) {
      this.dotter = new Dotter(this.message);
      this.dotter.start();
      this.request.options.url = this.options.url;
      this.message.addClass('dialog-loading');
      (function() {
        
    	this.request.send()
      }).delay(100, this);
      if (this.options.autoShow) this.delayedShow = true;
    } else if ($defined(this.options.message)){
      if (this.options.setMessageDelay) {
        (function() { this.setMessage(this.options.message); }).delay(
          this.options.setMessageDelay, this);
      } else {
        this.setMessage(this.options.message);
      }
    }

    // dialog footer
    if (this.options.footer !== false) {
      this.footer = new Element('div', {
        'id': this.options.id + '_footer',
        'class': this.options.footerClass
      }).inject(this.eMessage);

      new Element('div', {'class': 'foot-wrap'}).inject(this.footer);

      if (this.options.ok !== false) {
        this.createButton(
          'ok',
          this.options.id,
          this.options.okText,
          this.options.okClass,
          this.options.ok,
          !this.options.okDestroy,
          undefined,
          true
        ).inject(this.footer.firstChild, 'top');
      }
      if (this.options.cancel !== false) {
        this.createButton(
          'cancel',
          this.options.id,
          this.options.cancelText,
          this.options.cancelClass,
          this.options.cancel,
          !this.options.cancelDestroy
        ).inject(this.footer.firstChild, 'top');
      }

      if ($type(this.options.buttons) == 'object') {
        for (var btn in this.options.buttons) {
          btn = this.options.buttons[btn];
          this.createButton(
            btn.name,
            this.options.id,
            btn.text,
            btn.class_name,
            btn.action,
            !(btn.auto_close),
            ($defined(btn.tabindex) ? btn.tabindex : null)
          ).inject(this.footer.firstChild, 'top');
        }
      }
    }

    // set dialog to draggable
    if (this.options.draggable && this.titlebar) {
      this.drag = new Drag.Move(this.dialog, {
        handle: this.titlebar,
        onComplete: function() {
          window.fireEvent('dialogMove', this);
        }.bind(this)
      });
    }

    this.fireEvent('complete');
    this.init();
    
    if (this.options.resizeble) new this.options.resizeble(this);

    // execute onComplete function, if present.
    if (this.options.autoShow && !this.request.running) { this.show(); }
    
    window.document.currentDialog = this;
  },
  
  init: function() {},
  
  initReduceHeight: function(force) {
    if (force || !this.options.reduceHeight) return;
    //if (this.initHeight) return;
    this.initHeight = this.message.getSize().y;
    window.addEvent('resize', this.reduceHeight.bind(this));
    this.reduceHeight();
  },
  
  reduceHeight: function() {
    var maxH = window.getSize().y-150;
    if (this.initHeight < maxH)
      this.message.setStyle('height', this.initHeight+'px');
    else
      this.message.setStyle('height', maxH+'px');
  },
  
  setTitle: function(title) {
    this.prevTitle = this.options.title;
    this.title = title;
    this.titleText.set('html', title);
  },
  
  restorePrevTitle: function() {
    this.titleText.set('html', this.prevTitle);
  },

  setMessage: function(_message, delayedShow) {
    var message = ($type(_message) == 'function' ? _message() : _message);
    if (this.dotter) this.dotter.stop();
    if ($type(message) == 'element') {
      this.grabbed = message.getParent();
      if (this.grabbed != null) {
        message.removeClass('none');
        this.message.grab(message);
      } else {
        message.inject(this.message);
      }
    } else {
      this.message.set('html', message);
    }
    if (!$defined(delayedShow)) delayedShow = this.delayedShow;
    if (this.delayedShow && delayedShow) {
      this.delayedShow = false;
      this.show();
    }
    
    if (this.titlebar && this.btnClose) {
      this.titleText.setStyle('width',
        (this.titlebar.getSizeWithoutPadding().x
         - this.btnClose.getSizeWithMargin().x
         - 10) + 'px');
    }
    
    this.initReduceHeight();
    this.screen_center();
  },
  
  setOkText: function(text) {
    if (!this.btns.ok) return;
    this.btns.ok.getElement('a').set('html', this.getButtonInnerHtml(text));
  },
  
  toggle: function(name, flag) {
    if (!this.btns[name]) return;
    this.btns[name].setStyle('display', flag ? 'block' : 'none');
  },
  
  errorMessage: function(xhr) {
  },
  
  urlRequest: function(_response) {
    if (this.closed) return;
    this.message.removeClass('dialog-loading');
    this.dotter.stop();
    if (!this.options.jsonRequest) this.setMessage(_response, false);
    this.fireEvent('request', _response);
  },
  
  getButtonInnerHtml: function(text) {
    return '<i></i>' + text;
  },

  createButton: function(name, id, text, cls, action, unforceClose, tabindex, okClose) {
    var self = this;
    var eButton = new Element('div', { 'class': 'goright image-button ' + cls });
    var eLink = new Element('a', {
      id: id + '_' + name,
      href: 'javascript:void(0)', 
      tabindex: ($defined(tabindex) ? tabindex : (++this.tab_index)), 
      html: this.getButtonInnerHtml(text)
    }).inject(eButton);
    if (action && action instanceof Function) { eLink.addEvent('click', action); }
    if (!unforceClose) eLink.addEvent('click',
      okClose ? this.okClose.bind(this) : this.close.bind(this)
    );
    this.btns[name] = eButton;
    return eButton;
  },

  openShade: function() {
    if ($defined(this.eShade)) return;
    this.eShade = new Element('div', {
      'class': this.options.shadeClass,
      'styles': {
        'z-index': this.options.baseZIndex + (this.dialogN * 2) - 1
      }
    }).inject(document.body);
    return this;
  },
  
  closeShade: function() {
    this.eShade.dispose();
  },

  show: function() {
    if (this.options.force) this.openShade();

    this.dialog.setStyle('display', '');
    if (this.options.center !== false) {
      this.screen_center();
      //window.addEvent('resize', function() { this.screen_center(true); }.bind(this));
    }
    
    this.fireEvent('show');

    if (this.options.useFx) {
      this.fx.start('opacity', 0, 1);
    }
  },

  hide: function() {
    this.dialog.setStyle('display', 'none');
    this.fireEvent('hide');
  },

  okClose: function() {
    this.isOkClose = true;
    this.close();
  },
  
  close: function() {
    if (this.options.useFx) {
      this.fx.start('opacity', 1, 0).chain(this.finishClose.bind(this));
    } else { this.finishClose(); }
  },

  finishClose: function() {
    if ($(this.dialog)) {
      this.closed = true;
      
      if ($defined(this.grabbed)) {
        this.grabbed.grab(this.message.firstChild);
      }
      this.fireEvent('beforeClose');
      this.dialog.empty().dispose();
      Ngn.dialogs.erase(this.dialogId);
      
      if (this.options.force) this.closeShade();

      this.fireEvent('close');
      this.isOkClose ? this.fireEvent('okClose') : this.fireEvent('cancelClose');
    }
  },

  screen_center: function(fx) {
    var parXY = this.parentElement.getCoordinates();
    var parScroll = this.parentElement.getScroll();
    var elmXY = this.dialog.getCoordinates();
    var elmWH = this.dialog.getSize();
    var dialogH = Math.round((parXY.height - elmWH.y) / 5);
    if (dialogH < 20) dialogH = 20;
    if (this.options.center !== 'y') {
      if (fx) new Fx.Tween(this.dialog, { duration: 'short' }).start('left', ((parXY.width - elmWH.x) / 2) + 'px');
      else this.dialog.setStyle('left', ((parXY.width - elmWH.x) / 2) + 'px');
    }
    if (this.options.center !== 'x') {
      if (fx) new Fx.Tween(this.dialog, { duration: 'short' }).start('top', (dialogH + parScroll.y) + 'px');
      else this.dialog.setStyle('top', (dialogH + parScroll.y) + 'px');
    }
  },
  
  loading: function(flag) {
    flag ? this.footer.addClass('loading') : this.footer.removeClass('loading');
  }
  
});

Ngn.Dialog.Msg = new Class({
  Extends: Ngn.Dialog,
  
  options: {
    noPadding: false,
    messageAreaClass: 'dialog-message large',
    title: false
  }

});

Ngn.Dialog.Resizeble = new Class({
  
  initialize: function(dialog) {
    this.dialog = dialog;
    this.eHandle = new Element('div', {
      'class': 'handle bHandle'
    });
    this.dialog.dialog.addClass('resizeble');
    var eResizeble = this.getResizebleEl();
    var storeK = this.dialog.options.id+'_height';
    var h = Ngn.storage.get(storeK);
    if (h) eResizeble.setStyle('height', h+'px');
    new Drag(eResizeble, {
      preventDefault : true,
      stopPropagation: true,
      snap: 0,
      handle: this.eHandle,
      modifiers: {y: 'height', x: null},
      onComplete: function() {
        c(eResizeble.getSize().y);
        Ngn.storage.set(storeK, eResizeble.getSize().y);
      }
    });
    this.eHandle.inject(this.dialog.eMessage);
  },
  
  getResizebleEl: function() {
    return this.dialog.eMessage;
  }
  
});

Ngn.Dialog.Confirm = new Class({
  Extends: Ngn.Dialog.Msg,
  
  initialize: function(_opts) {
    var opts = $merge(_opts, {
      cancel: false,
      titleClose: false,
      ok: this.closeAction.bind(this, true),
      cancel: this.closeAction.bind(this, false)
    });
    this.parent(opts);
  },
  
  buildMessage: function(_msg) {
    var message_box = new Element('div');
    new Element('div', {'class':'icon-button confirm-icon goleft'}).inject(message_box);
    new Element('div', {'class':'mav-alert-msg goleft', 'html': _msg}).inject(message_box);
    new Element('div', {'class':'clear'}).inject(message_box);
    
    return message_box;
  },
  
  closeAction: function(_confirmed) {
    _confirmed ? this.okClose() : this.close();
  }
  
});

Ngn.Dialog.Prompt = new Class({
  Extends: Ngn.Dialog,

  initialize: function(_opts) {
    var opts = $merge(_opts, {
      cancel: false,
      titleClose: false,
      bindBuildMessageFunction: true,
      ok: this.closeAction.bind(this),
      cancel: this.closeAction.bind(this, false),
      onComplete: function() {
        var text_elem = this.dialogId + '_prompted';
        window.setTimeout(function() {
          $(text_elem).focus();
        }, 310);
      }
    });
    this.parent(opts);
  },

  buildMessage: function(_msg) {
    var message_box = new Element('div');
    new Element('div', {'class':'icon-button prompt-icon goleft'}).inject(message_box);
    var msg_display = new Element('div', {'class':'mav-alert-msg goleft'}).inject(message_box);

    new Element('div', {'html': _msg}).inject(msg_display);
    new Element('input', {
      'id': this.dialogId + '_prompted',
      'type':'text', 
      'class': 'mav-prompt-input'
    }).inject(msg_display);

    new Element('div', {'class':'clear'}).inject(message_box);

    return message_box;
  },
  
  closeAction: function(_canceled) {
    this.close();
    
    var prompt_value = (_canceled === false ? null : $(this.dialogId + '_prompted').get('value'));
    if (this.options.useFx && $defined(this.options.callback)) {
      // bah.
      this.fx.start('opacity', 1, 0).chain(this.finishClose.bind(this)).chain(this.options.callback(prompt_value));
    } else {
      this.finishClose();
      if ($defined(this.options.callback) && $type(this.options.callback) == 'function') {
        this.options.callback(prompt_value);
      }
    }
  }
});

Ngn.Dialog.Alert = new Class({
  Extends: Ngn.Dialog,
  
  options: {
    noPadding: false
  },

  initialize: function(_opts) {
    var opts = $merge(_opts, {
      cancel: false,
      titleClose: false,
      bindBuildMessageFunction: true
    });
    this.parent(opts);
  },

  buildMessage: function(msg) {
    var message_box = new Element('div');
    new Element('div', {'class':'icon-button alert-icon goleft'}).inject(message_box);
    new Element('div', {'class':'mav-alert-msg goleft', 'html': msg}).inject(message_box);
    new Element('div', {'class':'clear'}).inject(message_box);
    return message_box;
  }
});

Ngn.Dialog.Error = new Class({
  Extends: Ngn.Dialog.Alert,
  
  options: {
    title: 'Ошибка',
    width: 600
  },
  
  buildMessage: function(msg) {
    //throw new Error(this.options.error.message);
    return this.parent(
      '<p>'+this.options.error.message+' <i>Code: '+this.options.error.code+'</i></p>'+
      '<p>'+this.options.error.trace+'</p>'
    );
  }
  
});

/*
Ngn.Dialog.Wysiwyg = new Class({
  Extends: Ngn.Dialog,
  
  initialize: function(_opts) {
    var opts = $merge(_opts, {
      'ok': this.closeAction.bind(this),
      'bindBuildMessageFunction': true,
    });
    this.parent(opts);
  },
  
  buildMessage: function(_msg) {
    this.wysiwyg = new Wysiwyg({
      textarea: new Element('textarea', {
        'id': 'wisiwigDialog',
        'text': _msg
      }),
      css: this.options.css ? this.options.css : null
      //buttons: ['strong','em','u','superscript','subscript','ul','ol']
    });
    alert(this.wysiwyg.CT);
    return this.wysiwyg.CT;
  },
  
  closeAction: function(_canceled) {
    this.options.callback(this.wysiwyg.getHTML());
  }
  
});
*/

Ngn.Dialog.Textarea = new Class({
  Extends: Ngn.Dialog,

  initialize: function(_opts) {
    _opts.dialogClass = 'dialog dialog-textarea dialog-nopadding';
    var opts = $merge(_opts, {
      ok: this.closeAction.bind(this),
      bindBuildMessageFunction: true
    });
    this.parent(opts);
  },

  buildMessage: function(_msg) {
    this.id = 'textarea_' + this.options.id;
    this.eTextarea = new Element('textarea', {
      'id': this.id,
      'text': _msg
    });
    return this.eTextarea;
  },

  closeAction: function(_canceled) {
    this.options.callback(this.eTextarea.get('value'));
  }
  
});

Ngn.Dialog.Iframe = new Class({
  Extends: Ngn.Dialog,
  
  options: {
    // iframeStyles: {}
    okDestroy: false
  },
  
  iframeStyles: {
    'border': '0px',
    'width': '100%'
  },
  
  initialize: function(_opts) {
    //_opts.dialogClass = 'dialog dialog-textarea dialog-nopadding';
    var opts = $merge(_opts, {
      ok: this.okAction.bind(this),
      bindBuildMessageFunction: true
    });
    opts.iframeStyles = $merge({
      'border': '0px',
      'width': '100%',
      'height': '100%'
    }, opts.iframeStyles);
    this.parent(opts);
  },
  
  buildMessage: function() {
    this.eIframe = new Element('iframe', {
      'src': this.options.iframeUrl,
      'styles': this.options.iframeStyles
    });
    return this.eIframe;
  },
  
  okAction: function() {
    if (this.eIframe.contentWindow.okAction()) this.okClose();
  }
  
});

Ngn.Dialog.Captcha = new Class({
  Extends: Ngn.Dialog,
  
  options: {
    title: 'Введите код на картинке',
    bindBuildMessageFunction: true,
    dialogClass: 'dialog mav-center mav-captcha',
    width: 220,
    noPadding: false,
    okDestroy: false
  },
  
  initialize: function(opts) {
    var opts = $merge(opts, {
      ok: this.okAction.bind(this)
    });
    this.parent(opts);
    (function(){
      this.message.getElement('input').focus();
      this.captcha = this.message.getElement('.captcha');
      this.captchaHelp = this.message.getElement('.captchaHelp');
    }).delay(100, this);
  },
  
  buildMessage: function() {
    return '<div><img src="/s/captcha" class="captcha" /><input id="keystring" type="text" /><div><small class="captchaHelp">регистр не важен</small></div></div>'.toDOM()[0];
  },
  
  okAction: function() {
    this.loading(true);
    new Request({
      url: 'c/captcha/ajax_check',
      onComplete: function(r) {
        this.loading(false);
        if (r == 'failed') {
          this.captchaHelp.set('html', 'Вы ввели неправвильный код');
          this.captchaHelp.addClass('error');
          this.captcha.set('src', '/s/captcha?'+Math.random());
        } else {
          this.okClose();
        }
      }.bind(this)
    }).post({
      keystring: $('keystring').get('value')
    });
    return false;
  }
  
});

Ngn.Dialog.Shade = new Class({
  Extends: Ngn.Dialog,
  
  options: {
    autoShow: false
  }
});

Ngn.Dialog.HtmlPage = new Class({
  Extends: Ngn.Dialog,
  
  options: {
    noPadding: false,
    footer: false
  }
  
});