Ngn.FlagLink = new Class({
  
  Implements: [Options, Events],
  
  options: {
    textSelector: null,
    classOn: '',
    classOff: '',
    linkOn: '',
    linkOff: '',
    titleOn: 'Включить',
    titleOff: 'Выключить',
    onComplete: $empty()
  },
  
  initialize: function(el, options) {
    this.setOptions(options);
    this.el = $(el) || el;
    if (this.options.textSelector)
      this.text = this.el.getElement(this.options.textSelector);
    if (this.el.hasClass(this.options.classOn)) {
      this.el.set('title', this.options.titleOff);
      if (this.text) this.text.set('text', this.options.titleOff);
    } else {
      this.el.set('title', this.options.titleOn);
      if (this.text) this.text.set('text', this.options.titleOn);
    }
    this.el.addEvent('click', function(e) {
      new Event(e).stop();
      this.click();
    }.bind(this));
  },
  
  click: function() {
    var isOn = this.el.hasClass(this.options.classOn);
    new Request({
      url: isOn ? this.options.linkOff : this.options.linkOn,
      onComplete: function(data) {
        if (isOn) {
          this.el.removeClass(this.options.classOn);
          this.el.addClass(this.options.classOff);
          this.el.set('title', this.options.titleOn);
          if (this.text) this.text.set('text', this.options.titleOn);
        } else {
          this.el.removeClass(this.options.classOff);
          this.el.addClass(this.options.classOn);
          this.el.set('title', this.options.titleOff);
          if (this.text) this.text.set('text', this.options.titleOff);
        }
        this.fireEvent('complete', {
          isOn: isOn,
          options: this.options
        });
      }.bind(this)
    }).GET();
  }
  
});
