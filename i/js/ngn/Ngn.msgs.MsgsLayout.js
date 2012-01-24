Ngn.msgs.MsgsLayout = new Class({
  Extends: Ngn.msgs.Actions,
  
  Implements: [Options],
  
  options: {
    authorized: true
  },
  
  initialize: function(url, userUrl, options) {
    this.setOptions(options);
    this.objAnswerBlock = new Ngn.msgs.AnswerBlock($('answerBlock'), this);
    this.elAnsId = $('ansId');
    this.m = $('_msgs');

    
    this.eLoader = $('msgsLoader');

    var eFrom = $('msgForm');
    // msgText
    this.eMsgText = $('msgText');
    
    // Ctrl+Enter
    this.eMsgText.addEvent('keydown', function(e){
      if (e.key == 'enter' && e.control) {
        this.create();
      }
    }.bind(this));
    // btnSubmit
    this.btnCreate = $('btnSubmit');
    this.btnCreate.addEvent('click', function(e) {
      new Event(e).stop();
      this.create();
    }.bind(this));
    this.parent(url);
    
    this.eCreateTitle = this.btnCreate.getElement('span');
    this.createTitle = this.eCreateTitle.get('text');
    
    //if (!this.m) return; // нет списка сообщений
    this.userUrl = userUrl;
    this.users = {};
    this.m.getElements('div[class^=item]').each(function(elMsg, i) {
      var data = JSON.decode(elMsg.getElement('div[class=data]').get('html'));
      this.users[data.userId] = data.login;
      new Ngn.msgs.MsgLayout(
        data,
        elMsg.getElement('div[class~=editBlock]'),
        elMsg.getElement('a[class~=sm-answer]'),
        elMsg,
        this
      );
    }.bind(this));
    this.setAnchorAnswer();
    this.setItemAuthorAnswer();
  },
  
  create: function() {
    var text = trim(this.eMsgText.get('value'));
    if (!text) return;
    this.disableSubmitButton();
    var obj = this;
    if (!this.options.authorized) {
      new Ngn.Dialog.Auth({
        openerType: 'msgs',
        fromVkEnabled: true,
        onAuthComplete: function() {
          this._create(text);
          this.options.authorized = true;
        }.bind(this),
        onClose: function() {
          this.enableSubmitButton();
        }.bind(this),
      });
    } else {
      this._create(text);
    }
  },
  
  disableSubmitButton: function() {
    this.btnCreate.addClass('loading');
    this.eCreateTitle.set('html', '&nbsp');
  },
  
  enableSubmitButton: function() {
    this.eCreateTitle.set('text', this.createTitle);
    this.btnCreate.removeClass('loading');
  },
  
  
  setItemAuthorAnswer: function() {
    document.getElements('.t_author').each(function(el){
      /*
      el.setStyles({
        'float': 'left',
        'margin-right': '10px'
      });
      */
      var btnIAnswer = new Element('a', {
        'class': 'smIcons sm-answer gray',
        'href': '#',
        'html': 'ответить'
      });
      btnIAnswer.grab(new Element('i')).inject(el, 'after');
      new Element('div', {'class': 'clear'}).inject(btnIAnswer, 'after');
      var userId = btnIAnswer.get('href').replace(/^.*\/(\d+)$/, '$1');
      this.users[userId] = el.getElement('a').get('html');
      
      btnIAnswer.addEvent('click', function() {
        this.answerOn(
          0,
          userId,
          this.users[userId]
        );
        return false;
      }.bind(this));
    }.bind(this));
  },

  // Ссылка вида "/forum/92313#answer.0.1" удет обработана как ответ автору ID=1
  setAnchorAnswer: function() {
    var linkParts = new String(window.location).split('#');
    if (linkParts[1]) var anchor = linkParts[1];
    if (anchor) {
      var anchorParts = anchor.split('.');
      if (anchorParts[0] == 'answer') {
        this.answerOn(
          anchorParts[1], // msgId
          anchorParts[2], // userId
          this.users[anchorParts[2]] ? this.users[anchorParts[2]] : '[unknown]'
        );
      }
    }
  },

  answerOn: function(id, userId, login) {
    this.elAnsId.set('value', id);
    this.objAnswerBlock.switchOn(id, userId, login);
  },
  
  answerOff: function() {
    this.elAnsId.set('value', '');
    this.objAnswerBlock.switchOff();
  },
  
  addNewMsgs: function(data) {
    this.m.set('html', data.msgsHtml + this.m.get('html'));
    for (var i=0; i<data.msgsIds.length; i++) {
      new Fx.Slide($('msg' + data.msgsIds[i]), {
        duration: 1000,
        transition: Fx.Transitions.Pow.easeOut
      }).hide().slideIn();
      /*
      new Ngn.MsgLayout(
        JSON.decode(elMsg.getElement('div[class=data]').get('title')),
        elMsg.getElement('div[class~=editBlock]'),
        elMsg.getElement('a[class~=sm-answer]'),
        elMsg,
        this
      );
      */
    }
  },
  
  createComplete: function(data) {
    if (data) {
      if (data.error) {
        alert(data.error);
      } else {
        this.eMsgText.set('value', '');
        this.eMsgText.focus();
        this.objAnswerBlock.switchOff();
        this.addNewMsgs(data);
        if ($('youLlBeFirst')) {
          new Fx.Slide($('youLlBeFirst'), {
            duration: 500,
            transition: Fx.Transitions.Pow.easeOut
          }).slideOut();
        }
      }
    }
    this.enableSubmitButton();
  },
  
  refrash: function() {
    this.eLoader.addClass('loader');
    new Ngn.Request.JSON({
      url: this.url,
      onComplete: function(data) {
        this.eLoader.removeClass('loader');
        if (data.msgsHtml)
          this.addNewMsgs(data.msgsHtml);
      }.bind(this)
    }).GET({
      'sub' : 'msgs',
      'action': 'sub_json_refrash'
    });
  }
  
});
