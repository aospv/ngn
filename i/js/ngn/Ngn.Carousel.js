Ngn.Carousel = new Class({
  
  Extends: Fx.Scroll,
  
  SUCK: 1,
  
  options: {
    mode: 'horizontal',
    id: 'carousel',
    childSelector: false,
    loopOnScrollEnd: true
  },
  
  initialize: function(element, options){
    this.parent(element, options);
    this.cacheElements();
    for (var i=0; i<this.elements.length; i++) {
      this.elements[i].store('initIndex', i);
    }
    this.currentIndex = 0;
    this.elementWidth = this.elements[0].getSize().x;
    this.visibleElementsN = Math.round(this.element.getSize().x / this.elementWidth);
  },
  
  cacheElements: function(){
    var els;
    if (this.options.childSelector) {
      els = this.element.getElements(this.options.childSelector);
    } else if (this.options.mode == 'horizontal'){
      els = this.element.getElements(':first-child > *');
    } else {
      els = this.element.getChildren();
    }
    if (els[0] && this.options.changeContainerWidth && this.options.elementsOnPage) {
      this.element.setStyle('width', (els[0].getSize().x * this.options.elementsOnPage) + 'px');
      this.element.getFirst().setStyle('width', (els[0].getSize().x * els.length) + 'px');
    }
    this.elements = els;
    return this;
  },
  
  toNext: function(){
    if (!this.check()) return this;
    this.currentIndex = this.getNextIndex();
    if (!this.elements[this.currentIndex]) return; // masted fix
    this.toElement(this.elements[this.currentIndex]);
    this.fireEvent('next');
    return this;
  },
  
  toPrevious: function(){
    if (!this.check()) return this;
    this.currentIndex = this.getPreviousIndex();
    if (!this.elements[this.currentIndex]) return; // masted fix
    this.toElement(this.elements[this.currentIndex]);
    this.fireEvent('previous');
    return this;
  },
  
  toElement: function(el) {
    //if (this.checkLink()) return this;
    this.parent(el);
    this.fireEvent('toElement');
  },
  
  setRight: function() {
    this.set(this.element.getScrollSize().x, 0);
  },
  
  getNextIndex: function(){
    this.currentIndex++;
    if(this.currentIndex == this.elements.length || this.checkScroll()){
      this.fireEvent('loop');
      this.fireEvent('nextLoop');
      return 0;
    } else {
      return this.currentIndex;
    };
  },
  
  getPreviousIndex: function(){
    this.currentIndex--;
    var check = this.checkScroll();
    if(this.currentIndex < 0 || check) {
      this.fireEvent('loop');
      this.fireEvent('previousLoop');
      return (check) ? this.getOffsetIndex() : this.elements.length - 1;
    } else {
      return this.currentIndex;
    }
  },
  
  getOffsetIndex: function(){
    var visible = (this.options.mode == 'horizontal') ? 
      this.element.getStyle('width').toInt() / this.elements[0].getStyle('width').toInt() :
      this.element.getStyle('height').toInt() / this.elements[0].getStyle('height').toInt();
    return this.currentIndex + 1 - visible;
  },
  
  checkLink: function() {
    return (this.timer && this.options.link == 'ignore');
  },
  
  checkScroll: function(){
    if(!this.options.loopOnScrollEnd) return false;
    if(this.options.mode == 'horizontal'){
      var scroll = this.element.getScroll().x;
      var total = this.element.getScrollSize().x - this.element.getSize().x;
    } else {
      var scroll = this.element.getScroll().y;
      var total = this.element.getScrollSize().y - this.element.getSize().y;
    }
    return (scroll == total);
  },
  
  getCurrent: function(){
    return this.elements[this.currentIndex];
  }
  
});
