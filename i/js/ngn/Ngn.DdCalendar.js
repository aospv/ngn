Ngn.DdCalendar = new Class({
  
  Implements: [Options],
  
  options: {
    btnPrevSelector: '.ddCalendarBtns .prev a',
    btnNextSelector: '.ddCalendarBtns .next a'
  },
  
  initialize: function(eCalendar, options) {
    this.setOptions(options);
    this.init(eCalendar);
  },
  
  init: function(eCalendar) {
    this.eCalendar = document.id(eCalendar);
    this.eBtnPrev = eCalendar.getElement(this.options.btnPrevSelector);
    this.eBtnNext = eCalendar.getElement(this.options.btnNextSelector);
    this.eBtnPrev.addEvent('click', this.btnClickPrev.bind(this));
    this.eBtnNext.addEvent('click', this.btnClickNext.bind(this));
  },
  
  btnClickPrev: function(e){
    this.btnClick(e, this.eBtnPrev);
  },
  btnClickNext: function(e){
    this.btnClick(e, this.eBtnNext);
  },
  
  btnClick: function(e, eBtn){
    e.preventDefault();
    this.eCalendar.addClass('loader');
    new Request({
      url: eBtn.get('href'),
      onComplete: function(html) {
        this.eCalendar.removeClass('loader');
        this.eCalendar.set('html', html);
        this.init(this.eCalendar);
      }.bind(this)
    }).GET({
      'a': 'ajax_calendar'
    });
  }
  
});