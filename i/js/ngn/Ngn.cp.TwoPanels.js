Ngn.cp.TwoPanels = new Class({
  Implements: [Options],
  
  options: {
    storeId: 'twoPanels',
    leftExcludeEls: [],
    rightExcludeEls: [],
    addLeftWrapper: false,
    addRightWrapper: true
  },
  
  initialize: function(eLeft, eRight, eHandler, options) {
    this.setOptions(options);
    this.eLeft = eLeft;
    this.eRight = eRight;
    this.eHandler = eHandler;
    if (this.options.addLeftWrapper)
      this.eLeft = Ngn.addWrapper(this.eLeft, 'panelWrapper');
    if (this.options.addRightWrapper)
      this.eRight = Ngn.addWrapper(this.eRight, 'panelWrapper');
    Ngn.hHandler(this.eHandler, this.eLeft, this.options.storeId);
    // Элементы, высоты которых нужно вычитать не успевают отрендериться, поэтому ставим задержку
    (function() {
      this.init();
    }).delay(100, this);
  },
  
  leftMinusH: 0,
  //rightMinusH: 0,
  
  init: function() {
    window.addEvent('resize', function() {
      this.setHeight();
    }.bind(this));
    for (var i=0; i<this.options.leftExcludeEls.length; i++)
      this.leftMinusH = this.options.leftExcludeEls[i].getSize().y;
    this.setHeight();
  },
  
  getRightMinusHeight: function() {
    var h = 0;
    for (i=0; i<this.options.rightExcludeEls.length; i++)
      h += this.options.rightExcludeEls[i].getSize().y;
    return h;
  },
  
  setHeight: function() {
    var maH = Ngn.cp.getMainAreaHeight();
    this.eLeft.setSize({y: (maH - this.leftMinusH)});
    this.eRight.setStyle('height', (maH - this.getRightMinusHeight()) + 'px');
    this.eHandler.setStyle('height', maH + 'px');
  }
  
});

