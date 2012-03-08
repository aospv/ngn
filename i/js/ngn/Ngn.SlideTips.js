Ngn.SlideTips = new Class({
  
  Implements: [Events, Options],
  
  options: {
    cycled: true,
    onComplete: $empty, 
    onShow: $empty
  },
  
  initialize: function(element, options) {
    this.setOptions(options);
    this.fxs = new Array();
    this.id2i = {};
    this.element = element;
    this.btnClose = this.element.getElement('.sm-delete');
    this.ePrev = this.element.getElement('.sm-prev');
    this.eNext = this.element.getElement('.sm-next');
    this.eNums = this.element.getElement('.nums');
    this.n = 0;
    this.process = false;
    this.cycled = $defined(this.options.cycled) ? true : false;

    this.element.setStyle('display', 'none');
    
    this.element.getElement('.slides').getElements('div.slide').each(function(el, i){
      this.fxs[i] = new Fx.Slide(el, {
        duration: 300,
        transition: Fx.Transitions.Pow.easeOut,
        onComplete: function() {
          this.fireEvent('onComplete', this);
        }.bind(this)
      }).hide();
    }.bind(this));

    this.btnClose.addEvent('click', function(e) {
      e.preventDefault();
      this.element.dispose();
    }.bind(this));
    
    this.ePrev.addEvent('click', function(e){
      e.preventDefault();
      this.prev();
    }.bind(this));
    
    this.eNext.addEvent('click', function(e){
      e.preventDefault();
      this.next();
    }.bind(this));

    // Отображаем первую вкладку
    (function(){
      this.element.setStyle('display', 'block');
      this._show(0);
      this.fireEvent('onShow', this);
    }).delay(300, this);
    this.init();
  },
  init: function() {
  },
  getTotal: function() {
    return this.fxs.length;
  },
  getPrevN: function() {
    //if (!$defined(this.curN)) alert('this.curN not defined');
    return (this.curN == 0) ? this.getTotal()-1 : this.curN - 1;
  },
  getNextN: function() {
    //if (!$defined(this.curN)) alert('this.curN not defined');
    return (this.curN == this.getTotal()-1) ? 0 : this.curN + 1;
  },
  next: function() {
    if (this.nextDisabled) return;
    this.show(this.getNextN());
  },
  prev: function() {
    if (this.prevDisabled) return;
    this.show(this.getPrevN());
  },
  ////////////////////////////////
  // Пользовательская ф-я
  show: function(n) {
    if (this.process) return;
    // Процесс перелистывания начался
    // До того момента, как он не закончиться, переключать нельзя
    this.process = true;
    if ($defined(this.curN)) { // Уже существует открытая секция
      this.fxs[this.curN].slideOut().chain(function() { // Закрываем секцию
        this._show(n); // Отображаем секцию N
      }.bind(this));
    } else {
      // Первое открытие
      this._show(n);
    }
  },
  _show: function(n) {
    this.curN = n; // Назначаем текущий номер открытой вкладки
    // Смена вкладки произошла
    this.disableBtns();
    // Выставляем текст для номеров страниц
    this.eNums.set('text', (n+1)+'/'+this.getTotal());
    this.fxs[n].slideIn().chain(function(){
      this.process = false; // Процесс закончился
    }.bind(this));
    
    Ngn.setToTopRight(this.btnClose, this.element, [5, 18]);
  },
  ////////////////////////////////
  disableBtns: function() {
    if (this.getTotal() == 1) {
      this.ePrev.setStyle('display', 'none');
      this.eNext.setStyle('display', 'none');
      this.eNums.setStyle('display', 'none');
    }
    if (this.cycled) return;
    this.ePrev.removeClass('disabled');
    this.eNext.removeClass('disabled');
    this.prevDisabled = false;
    this.nextDisabled = false;
    if (this.curN == 0) {
      this.ePrev.addClass('disabled');
      this.prevDisabled = true;
    }
    if (this.curN == this.getTotal()-1) {
      this.eNext.addClass('disabled');
      this.nextDisabled = true;
    }
  },
  removeCurrentSlide: function() {
    this.removeSlide(this.curN);
  },
  removeSlide: function(n) {
    this.fxs[n].element.getParent().dispose();
    this.fxs[n] = null;
    this.fxs = this.fxs.clean();
    if (!this.getTotal()) {
      this.element.dispose();
    } else {
      this._show(n > 1 ? --n : 0);
    }
    this.fireEvent('onRemove', this);
  }
  
});
