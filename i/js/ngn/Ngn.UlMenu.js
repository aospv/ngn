Ngn.UlMenu = new Class({
  
  Implements: [Options],
  
  options: {
    containerClass: 'bcont',
    containerTag: 'div'
  },
  
  initialize: function(element, options) {
    this.setOptions(options);
    this.element = document.id(element);
    this.element.getElements('li').each(function(eLi){
      if (eLi.hasClass('selected')) {
        var ePar = eLi.getParent();
        while (1) {
          if (
            ePar.get('class') == this.options.containerClass && 
            ePar.get('tag') == this.options.containerTag
          ) break;
          if (ePar.get('tag') == 'ul')
            ePar.setStyle('display', 'block');
          ePar = ePar.getParent();
        }
      }
      var eA = eLi.getElement('a');
      eA.addEvent('click', function(e) {
        var eUl = eA.getNext();
        if (!eUl) return;
        if (eUl.getStyle('display') == 'none') {
          eUl.setStyle('display', 'block');
          eLi.addClass('opened');
        } else {
          eUl.setStyle('display', 'none');
          eLi.removeClass('opened');
        }
        return false;
      });
    }.bind(this));
  }

});
