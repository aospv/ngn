Ngn.msgs.MsgLayout = new Class({
  
  initialize: function(data, elEditBlock, elAns, elMsg, objMsgs) {
    this.id = data.id;
    this.userId = data.userId;
    this.login = data.login;
    this.objMsgs = objMsgs;
    this.elMsg = elMsg;                         // Контейнер всей записи сообщения
    this.elAns = elAns;
    this.elBlock = elEditBlock;                 // Кнопки "редактировать", "активировать", "удалить"
    
    new Ngn.msgs.EditLayout(this.id, this.elBlock, this, this.objMsgs);
    
    this.elWrapper = $('msgText'+this.id);      // Контейнер для сообщения
    this.viewText = this.elWrapper.get('html'); // Текущий HTML сообщения
    this.elBtnOk = new Element('input', {       // Кнопка "Сохранить"
      'type': 'button',
      'value': 'Сохранить (Ctrl + Enter)',
      'styles' : {'width' : '150px'},
      'events': {
        'click': function(){
          this.action_ok();
        }.bind(this)
      }
    });
    this.elBtnCancel = new Element('input', {   // Кнопка "Отменить"
      'type': 'button',
      'value': 'Отменить',
      'events': {
        'click': function(){
          this.action_cancel();
        }.bind(this)
      }
    });
    if (this.elAns) {
      this.elAns.addEvent('click', function(e){
        this.objMsgs.answerOn(this.id, this.userId, this.login);
        return false;
      }.bind(this));
    }
    this.elMsgEditBlock = new Element('div', {'class': 'msgEditBlock'});
    this.elTextarea = new Element('textarea');  // Текстовое поле
    this.elTextarea.inject(this.elMsgEditBlock);
    //new AutoGrow(this.elTextarea);
    //Ngn.ResizableTextarea(this.elTextarea);
    this.elBtnOk.inject(this.elMsgEditBlock);
    this.elBtnCancel.inject(this.elMsgEditBlock);
    this.addSubmitEvent();
  },

  addSubmitEvent: function() {
    this.elTextarea.addEvent('keydown', function(e){
      if (e.key=='enter' && e.control) {
        this.action_ok();
      }
    }.bind(this));
  },
  
  switchEdit: function(text) {
    this.elWrapper.set('html', '');
    this.elTextarea.set('value', text);
    this.elWrapper.addClass('editing');
    this.elMsgEditBlock.inject(this.elWrapper);
    this.elBtnOk.set('disabled', false);
    this.elTextarea.focus();
  },
  
  switchView: function(text) {
    this.viewText = text;
    this.elWrapper.removeClass('editing');
    this.elWrapper.set('html', text);
  },
  
  activate: function() {
    //this.elWrapper.set('styles', {'color': 'default'});
  },
  
  deactivate: function() {
    //this.elWrapper.set('styles', {'color': '#CCCCCC'});
  },
  
  
  action_ok: function() {
    this.objMsgs.updateText(this.id, this.elTextarea.get('value'), this);
    this.elBtnOk.set('disabled', true);
  },
  
  action_cancel: function() {
    this.switchView(this.viewText);
  },
  
  action_edit: function(id) {
    this.objMsgs.getText(id, this);
  },
  
  action_activate: function(id) {
    this.objMsgs.activate(id, this);
  },
  
  action_deactivate: function(id) {
    this.objMsgs.deactivate(id, this);
  },
  
  action_delete: function(id) {
    this.objMsgs._delete(id, this);
  }
  
});
