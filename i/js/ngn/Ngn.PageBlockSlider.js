Ngn.PageBlockSlider = new Class({
  
  Implements: [Options],

  options: {
    headerSelector: 'h2'
  },
  
  initialize: function(el, options) {
    this.element = el;
    this.id = el.get('id').replace('block_', '');
    this.curN = 1;
    this.setOptions(options);
    this.header = el.getElement(this.options.headerSelector);
    this.element.addClass('blockSlider');
    this.nav = new Element('div', {
      'class': 'blockSliderNav smIcons bordered'
    });
    this.prevBtn = new Element('a', {
      'href': '#',
      'class': 'sm-prev',
      'title': 'Предыдущий'
    });
    this.nextBtn = new Element('a', {
      'href': '#',
      'class': 'sm-next',
      'title': 'Следующий',
      'events': {
        'click': function() {
          this.next();      
        }.bind(this)
      }
    });
    new Element('i').inject(this.prevBtn);
    new Element('i').inject(this.nextBtn);
    this.prevBtn.inject(this.nav);
    this.nextBtn.inject(this.nav);
    this.nav.inject(this.header, 'before');
    this.cacheNext();
  },
  
  next: function() {
  },
  
  cacheNext: function() {
    new Request({
      url: './c/pageBlock?a=ajax_getBlock',
      onComplete: function(html) {
        new Element('div', {
          'class' : 'items',
          'html': html
        }).inject(
          this.element.getElement('.items'), 'after'
        );
        /*
        new Fx.Scroll.Carousel.Loop(this.element, {
          childSelector: '.items',
          mode: 'horizontal',
          reverse: true
        });
        */
      }.bind(this)
    }).GET({
      id: this.id,
      pg: this.curN + 1
    });
  }
  
});