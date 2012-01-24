var TipsFixed = new Class( {

  Extends: Tips,

  /*
   * Fixed the element.getParent() is not a function bug by not 
   * only checking for element but for the getParent() as a function
   * 
   * Works for Moo-More 1.2.4.1+2
   * ---------------------------------------------------
   */

  fireForParent: function(event, element) {
    if (element && typeof element.getParent() == 'function') {
      parentNode = element.getParent();
      if (parentNode == document.body)
        return;
      if (parentNode.retrieve('tip:enter'))
        parentNode.fireEvent('mouseenter', event);
      else
        this.fireForParent(parentNode, event);
    } else
      return;
  }

});