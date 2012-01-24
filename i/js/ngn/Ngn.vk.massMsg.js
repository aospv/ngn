Ngn.vk = {};

Ngn.vk.massMsg = {};

Ngn.vk.massMsg.Dialog = new Class({
  Extends: Ngn.PartialJob.Dialog,
  
  options: {
    width: 600,
    title: 'Рассыльщик сообщений Вконтакте',
    startText: 'Разослать',
    pjOptions: {
      url: '/c/vk/json_pjSend',
      stepDelay: 10000
    }
  },
  
  buildMessage: function() {
    return this.parent() + '<textarea id="massMsgText"></textarea>';
  },
  
  init: function() {
    this.parent();
    this.eMsg = this.message.getElement('#massMsgText')
    Ngn.frm.storable(this.eMsg);
    this.start();
    this.setTitle('Происходит авторизация');
    new Ngn.Request.JSON({
      url: '/c/vk/json_info',
      onComplete: function(r) {
        this.stop();
        var t = 'Друзей: ' + r.friends.length + '. Отправлено: ' + r.sentUsers.length;
        if (r.lastSentUser) {
          t += '. Последний: <a href="http://vkontakte.ru/id'+r.lastSentUser.id+'" target="_blank">' +
            r.lastSentUser.name + '</a>';
        }
        this.setTitle(t);
        this.initPjContinue();
      }.bind(this)
    }).send();
    this.pj.addEvent('error', function(error) {
      if (error.code == 324234) {
        this.stopPj();
        this.setTitle('Отправка продолжится через 15 минут');
        this.msgSendTimeoutId = (function() {
          this.setTitle('Продолжаем отправку');
          this.startPj();
        }).delay(15*60000, this);
      } else {
        this.stopPj();
        this.setTitle('Неизвестная ошибка. Остановлено');
      }
    }.bind(this));
    this.addEvent('close', function() {
      $clear(this.msgSendTimeoutId);
    }.bind(this));    
  },
  
  initPjContinue: function() {
    new Ngn.Request.JSON({
      url: '/c/vk/json_getLastStep',
      onComplete: function(lastStep) {
        this.pj.curStep = lastStep;
        this.setTitle(this.title + '. Шаг: ' + lastStep);
      }.bind(this)
    }).send();
  },
  
  startPj: function() {
    this.continuePj();
  },
  
  continuePj: function() {
    if (!trim(this.eMsg.get('value'))) {
      alert('Сообщение пустое');
      return;
    }
    this.eMsg.set('disabled', true);
    this.pj.options.requestParams.message = this.eMsg.get('value');
    this.parent();
  },
  
  stopPj: function() {
    this.eMsg.set('disabled', false);
    this.parent();
  }
  
});