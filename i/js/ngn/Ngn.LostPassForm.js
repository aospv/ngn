Ngn.LostPassForm = new Class({
  initialize: function() {
    this.eEmail = $('lp_email');
    if (!this.eEmail) return;
    var defEmail = 'Ваш e-mail';
    this.eEmail.set('value', defEmail);
    this.eEmail.addEvent('focus', function(e) {
      if (this.eEmail.get('value') == defEmail) this.eEmail.set('value', '');
    }.bind(this));
    this.eEmail.addEvent('blur', function(e) {
      if (this.eEmail.get('value') == '') this.eEmail.set('value', defEmail);
    }.bind(this)); 
    
    this.eSend = $('lp_send');
    this.eLoader = $('lp_loader');
    this.eEmail.addEvent('keydown', function(e) {      
      if (e.key=='enter') this.send();                
    }.bind(this));
    this.eSend.addEvent('click', function(e) {
      e.preventDefault();
      if (this.eEmail.get('value') == defEmail) {
        alert('Введите e-mail');
        return;
      }
      this.send();
    }.bind(this));
    /////////
    this.eEmail.focus();
  },
  send: function() {
    this.eLoader.addClass('loaderDG');
    this.eSend.setStyle('display', 'none');
    new Ngn.Request.JSON({
      url: '/c/users?action=json_sendLostPass',
      onComplete: function(data) {
        this.eLoader.removeClass('loaderDG');
        if (!data) {
          $('lp_text').set('text', 'Неизвестная ошибка');
          return;
        }
        if (data['success']) {
          $('lp_text').set('text', 'Выслано успешно');
        } else {
          $('lp_text').set('text', 'Ошибка');
          this.eSend.setStyle('display', 'block');
        }
      }.bind(this)
    }).GET({
      email: this.eEmail.get('value')
    });
  }  
});
