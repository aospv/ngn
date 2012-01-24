Ngn.LayoutTile = new Class({
  
  initialize: function(eItems) {
    this.eItems = $(eItems);
    var maxY = 0;
    var vPadding = 0;
    this.eItems.getElements('div[class~=item]').each(function(el, n) {
      if (n == 0) {
        vPadding += el.getStyle('padding-top').toInt();
        vPadding += el.getStyle('padding-bottom').toInt();
        vPadding += el.getStyle('border-top-width').toInt();
        vPadding += el.getStyle('border-bottom-width').toInt();
      }
      var y = el.getSize().y;
      if (y > maxY) maxY = y;
    });
    if (!maxY) return;
    maxY = maxY - vPadding;
    this.eItems.getElements('div[class~=item]').each(function(el){
      el.setStyle('height', maxY);
    });
  }

});