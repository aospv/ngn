Ngn.site.photoCarousel = new Class({
  
  initialize: function() {
    this.eBcont = $('layout').getElement('.pbt_otherItems .bcont');
    this.eItems = $('layout').getElement('.pbt_otherItems .ddItems');
    this.eCarousel = new Element('div', { 'class': 'carousel' }).wraps(this.eItems);
    this.eHeader = new Element('div', {'class': 'header arrowsNav'}).inject(this.eBcont, 'top');
    
    this.esItems = this.eItems.getElements('.item');
    this.yn = 4;
    this.xn = Math.ceil(this.esItems.length / this.yn);
    this.visibleX = 2;
    
    this.buildParts();
    this.buildDummys();
    this.initConainerSizes();
    
    // Если картинок больше, чем убирается в экраны, инициализируем карусель
    if (this.visibleX * this.yn < this.esItems.length)
      this.initCarousel();
    
    this.selectActive();
  },
  
  initConainerSizes: function() {
    // переопределяем размеры контейнеров
    var elSize = this.esItems[0].getSizeWithMargin();
    this.eItems.setStyles({
      'height': elSize.y * this.yn
    });
    
    this.eCarousel.setStyles({
      'width': elSize.x * this.visibleX,
      'height': elSize.y * this.yn
    });
  },
  
  buildParts: function() {
    // инкапсульруем все элементы в parts
    this.esItemParts = [];
    for (var i=0; i < this.esItems.length; i++) {
      var partN = Math.floor(i / this.yn);
      if (i % this.yn == 0) {
        // первый элемент из группы. создаем перед ним контейнер, перемещаем его и
        // последующие элементы в контецнер
        this.esItemParts[partN] = new Element('div', {'class':'itemPart'}).
          inject(this.esItems[i], 'before');
      }
      this.esItems[i].inject(this.esItemParts[partN]);
    }
  },
  
  buildDummys: function() {
    // добавляем заглушки на место недостающих в конце
    var maxN = this.yn*this.xn;
    if (maxN > this.esItems.length) {
      var emptyN = maxN - this.esItems.length;
      var eEmpty = this.esItems[0].clone();
      eA = eEmpty.getElement('.thumb');
      eA.removeProperty('href');
      eA.set('class', eA.get('class')+' disabled');
      eEmpty.getElement('img').set('src', '/i/img/empty.png');
      for (var i=0; i<emptyN; i++) {
        eEmpty.clone().inject(this.esItemParts[this.esItemParts.length-1], 'bottom');
      }
    }
  },
  
  initCarousel: function() {
    // карусель
    this.carousel = new Ngn.Carousel.Loop(this.eCarousel, {
      childSelector: '.itemPart',
      mode: 'horizontal'
    });
    this.carousel.setElementIndex(this.visibleX);
    this.buildButtons();
    
    this.eBcont.addEvent('mousewheel', function(e) {
      e.preventDefault();
      if (e.wheel > 0) {
        this.carousel.toPrevious();
      } else {
        this.carousel.toNext();
      }
    }.bind(this));
  },
  
  buildButtons: function() {
    // кнопочки
    this.btnNext = Ngn.opacityBtn(new Element('a', {'class': 'next', title: 'следующая страница', href: '#'}));
    this.btnNext.inject(this.eHeader);
    this.btnNext.addEvent('click', function() {
      this.carousel.toNext();
      return false;
    }.bind(this));
    this.btnPrev = Ngn.opacityBtn(new Element('a', {'class': 'prev', title: 'предыдущая страница', href: '#'}));
    this.btnPrev.inject(this.eHeader);
    this.btnPrev.addEvent('click', function() {
      this.carousel.toPrevious();
      return false;
    }.bind(this));
  },
  
  getPartN: function(itemElementN) {
    return Math.ceil(itemElementN / this.yn); 
  },
  
  selectActive: function() {
    // скролим к ней
    var activeId = Ngn.getParam(1);
    var selectedN = null;
    for (var i=0; i<this.esItems.length; i++) {
      if (activeId == this.esItems[i].get('data-id')) {
        selectedN = i;
        break;
      }
    }
    if (selectedN !== null) {
      if (this.carousel)
        this.carousel.setElementIndex(this.getPartN(selectedN)+1);
      // добавляем класс
      this.esItems[selectedN].addClass('selected');
    }
  }
  
});
