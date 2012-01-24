Ngn.InputDefaultText = new Class({
  
  initialize: function(element) {
    this.element = document.id(element);
    this.defValue = this.element.get('value');
    this.element.addEvent('focus', function(e) {
      if (this.element.get('value') == this.defValue) eLogin.set('value', '');
    }.bind(this));
    this.element.addEvent('blur', function(e) {
      if (this.element.get('value') == '') eLogin.set('value', defLogin);
    }.bind(this));
  }
    
});
