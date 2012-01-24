/**

html:
<div id="carousel"> 
  <div class="inner">
    <p><a href="..."><img src="..." /></a></p>
  </div>
</div>

js:
new Ngn.Carousel.Loop('carousel', {
  childSelector: ':first-child > p',
  mode: 'horizontal',
  reverse: true
});

css:
#carousel {
  overflow: hidden;
  width: X;
}
#carousel p {
  width: FIXED;
}
#carousel .inner {
  width: X * N;
}

*/
Ngn.Carousel.Loop = new Class({
  
  Extends: Ngn.Carousel,
  
  options: {
    reverse: false,
    debugNumbers: false,
    container: null
  },
  
  initialize: function(element, options) {
    this.parent(element, options);
    
    this.initElementsN = this.elements.length;
    this.initElementsWidth = this.elementWidth * this.initElementsN;
    
    this.initIndex = this.options.reverse ? this.elements.length - 1 : 0;
    this.initX = this.options.reverse ? container.getSize().x : 0;

    // копируем кол-во видимых элементов из конца в начало
    for (var i=this.initElementsN-this.visibleElementsN; i < this.initElementsN; i++) {
      this.elements[i].clone().inject(
        this.elements[0], 'before').
        store('initIndex', this.elements[i].retrieve('initIndex'));
    }
    // копируем кол-во видимых элементов из начала в конец
    for (var i=this.visibleElementsN-1; i>=0; i--) {
      this.elements[i].clone().inject(
        this.elements[this.initElementsN-1], 'after').
        store('initIndex', this.elements[i].retrieve('initIndex'));
    }

    this.cacheElements();
    
    this.container = this.elements[0].getParent();
    this.container.setStyle('width', this.elements.length * this.elementWidth);
    
    if (this.options.reverse) {
      this.currentIndex = this.initIndex;
      this.set(this.initX, 0);
    }
    
    for (var i=0; i<this.elements.length; i++) {
      this.elements[i].store('index', i);
    }
    
    if (this.options.debugNumbers) {
      // add element number
      for (var i=0; i<this.elements.length; i++) {
        this.elements[i].setStyle('position', 'relative');
        ('<div style="position:absolute; background:#FF0000; padding: 2px 5px; top:15px; left:15px; color:#FFFFFF">'+(i)+'</div>').toDOM()[0].inject(this.elements[i]);
        ('<div style="position:absolute; background:#555555; padding: 2px 5px; top:40px; left:15px; color:#FFFFFF">'+(this.elements[i].retrieve('initIndex'))+'</div>').toDOM()[0].inject(this.elements[i]);
      }
    }
    
    this.setElementIndex(0);
  },
  
  toPrevious: function() {
    return this.toElementIndex(this.currentIndex-1);
  },
  
  toNext: function() {
    return this.toElementIndex(this.currentIndex+1);
  },
  
  toElementIndex: function(index) {
    if(this.checkLink()) return this;
    if (index < 0) {
      index += this.nextScreen(); // сначало меняем положение моментально
      return this.toElementIndex(index); // потом повторяем сдвиг для узмененного индекса 
    }
    else if (index+this.visibleElementsN > this.elements.length) {
      index += this.prevScreen();
      return this.toElementIndex(index);
    }
    this.currentIndex = index;
    this.toElement(this.elements[this.currentIndex]);
    return this.currentIndex;
  },
  
  setElementIndex: function(index) {
    this.set(index*this.elementWidth, 0);
    this.currentIndex = index;
    //this.fireEvent('indexChange');
  },
  
  // возаращает дельту количества элементов сдвига
  nextScreen: function() {
    this.setElementIndex(this.currentIndex+this.initElementsN);
    return this.initElementsN;
  },
  
  // возаращает дельту количества элементов сдвига
  prevScreen: function() {
    this.setElementIndex(this.currentIndex-this.initElementsN);
    return -this.initElementsN;
  }
  
});