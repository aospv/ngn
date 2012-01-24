Ngn.Carousel.implement({
  
  selectableInit: function() {
    this.selectOffset = Math.floor(this.visibleElementsN / 2);
    this.elements.each(function(el, i){
      el.addEvent('mouseover', function() {
        this.addClass('over');
      });
      el.addEvent('mouseout', function() {
        this.removeClass('over');
      });
      el.addEvent('click', function() {
        this.select(i);
      }.bind(this));
    }.bind(this));
    this.element.addEvent('mousewheel', function(e){
      e.wheel > 0 ? this.toPrevious() : this.toNext();
    }.bind(this));
    this.addEvent('toElement', function(){
      Ngn.storage.set(this.options.id+'index', this.currentIndex);
    }.bind(this));
    var savedIndex = Ngn.storage.int.get(this.options.id+'index');
    if (savedIndex) this.setElementIndex(savedIndex);
  },
  
  select: function(index) {
    if (this.checkLink()) return this;
    
    if ($defined(this.selectedIndex)) {
      // 0
      this.elements[this.selectedIndex].removeClass('sel');
      // -1
      if (this.elements[this.selectedIndex - this.initElementsN])
        this.elements[this.selectedIndex - this.initElementsN].removeClass('sel');
      // +1
      if (this.elements[this.selectedIndex + this.initElementsN])
        this.elements[this.selectedIndex + this.initElementsN].removeClass('sel');
    }
    
    this.toElementIndex(index-this.selectOffset);
    
    this.selectedIndex = this.currentIndex + this.selectOffset;

    // 0
    this.elements[this.selectedIndex].addClass('sel');
    // -1
    if (this.elements[this.selectedIndex - this.initElementsN])
      this.elements[this.selectedIndex - this.initElementsN].addClass('sel');
    // +1
    if (this.elements[this.selectedIndex + this.initElementsN])
      this.elements[this.selectedIndex + this.initElementsN].addClass('sel');
    
    this.fireEvent('select', this.elements[this.selectedIndex].retrieve('initIndex'));
  },
  
  getSelectedInitIndex: function() {
    this.elements[this.selectedIndex].retrieve('initIndex');
  }
  
});

Ngn.Carousel.Select = new Class({
  
  initialize: function(carousel, btns) {
    this.carousel = carousel;
    this.carousel.selectableInit();
    // btns
    this.btns = btns;
    Ngn.setToCenterRelVer(this.btns[0]);
    Ngn.setToCenterRelVer(this.btns[1]);
    Ngn.opacityBtn(this.btns[0], 0.1, 0.4);
    Ngn.opacityBtn(this.btns[1], 0.1, 0.4);
    this.btns[0].addEvent('click', function() {
      this.carousel.toPrevious();
    }.bind(this));
    this.btns[1].addEvent('click', function() {
      this.carousel.toNext();
    }.bind(this));
  }

});
