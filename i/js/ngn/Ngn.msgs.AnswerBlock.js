Ngn.msgs.AnswerBlock = new Class({
  
  initialize: function(elBlock, objMsgs) {
    if (!elBlock) alert('elBlock not defined');
  
    this.elBlock = elBlock;
    this.objMsgs = objMsgs;
    
    this.elUser = new Element('div', {      // Надписть "Вы отвечаете пользователю"
    });
    this.elUser.inject(this.elBlock);
    
    this.elBtnCancel = new Element('a', {   // Кнопка "Отменить ответ"
      'href': 'button',
      'class': 'gray',
      'events': {
        'click': function(e){
          e.preventDefault();
          this.objMsgs.answerOff();
        }.bind(this)
      }
    });
    this.elBtnCancel.set('html', 'Не отвечать');    
    this.elBtnCancel.inject(this.elBlock);
  },
  
  switchOn: function(id, userId, login) {
    this.elUser.set('html', 'Вы отвечаете → <a href="' + 
      this.objMsgs.userUrl.replace(311, userId) + '">' + login + '</a>');
    this.elBlock.set('styles', {'display': 'block'});
    //window.location = '#msgAdd';
    var msgNick = $('msgNick');
    msgNick ? msgNick.focus() : $('msgText').focus();
  },
  
  switchOff: function() {
    this.elBlock.set('styles', {'display': 'none'})
  }
  
});
