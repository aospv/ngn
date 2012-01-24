Ngn.HorizontalMenu = new Class({
  
  Implements: [Options],
  
  options: {
    createExtraElements: false,
    borderElement: null,
    openLeftOffset: 0
  },
  
  initialize: function(eDiv, options) {
    if (!eDiv) return;
    this.setOptions(options);
    this.eBorder = this.options.borderElement;
    this.eUl = eDiv.getElement('ul');
    this.eUl.getChildren('li').each(function(eLi){
      if (this.options.createExtraElements) {
        var eA = eLi.getFirst('a');
        new Element('div', {'class': 'clear'}).inject(eA, 'after');
        new Element('i').inject(eA, 'after');
      }
      eLi.addEvent('mouseover', function() {
        $clear(eLi.retrieve('timerId'));
        this.show(eLi);
      }.bind(this));
      eLi.addEvent('mouseout', function(e) {
        eLi.store('timerId', (function(){
          this.hide(eLi);
        }).delay(50, this));
      }.bind(this));
    }.bind(this));
    this.init();
  },
  
  init: function() {},
  
  show: function(eLi) {
    if (eLi.hasClass('over')) return false;
    eLi.addClass('over');
    if (this.eBorder) {
      var eSubUl = eLi.getElement('ul');
      if (eSubUl) {
        var r = (eSubUl.getPosition().x + eSubUl.getSize().x) - 
        (this.eBorder.getPosition().x + this.eBorder.getSize().x);
        if (r > 0) {
          eSubUl.setStyle('left', -(eSubUl.getSize().x-eLi.getSize().x+this.options.openLeftOffset));
          eLi.addClass('openLeft');
        } else {
          eLi.addClass('openRight');
        }
      } else return false;
    }
    return true;
  },
  
  hide: function(eLi) {
    eLi.removeClass('over');
  }
  
});
