Ngn.hidebleBarIds = [];
Ngn.HidebleBar = new Class({
  
  initialize: function(eBar, mode) {
    this.mode = mode;
    this.id = Ngn.hidebleBarIds.length + 1;
    Ngn.hidebleBarIds.push(this.id);
    this.eBar = document.id(eBar);
    this.eBar.setStyle('position', 'relative');
    this.eHandlerHide = new Element('div', {'class': 'hidebleBarHandler'});
    this.eHandlerHide.addClass(mode);
    this.eHandlerShow = new Element('div', {'class': 'hidebleBarHandler'});
    this.eHandlerShow.addClass(mode == 'down' ? 'up' : 'down');
    this.eHandlerHide.addClass('hide');
    this.eHandlerShow.addClass('show');
    Ngn.addHover(this.eHandlerHide, 'hover');
    Ngn.addHover(this.eHandlerShow, 'hover');
    this.eHandlerHide.inject(this.eBar);
    this.eHandlerShow.inject(document.getElement('body'));
    this.positionHandlerShow();
    
    if (mode == 'up')
      this.eHandlerHide.setStyle('top', this.eBar.getSize().y-this.eHandlerHide.getSize().y);
    
    window.addEvent('resize', this.position.bind(this));
    Ngn.setToCenterHor(this.eHandlerHide, this.eBar);
    Ngn.setToCenterHor(this.eHandlerShow, this.eBar);
    this.eHandlerShow.setStyle('visibility', 'hidden');
    var hh = this.eHandlerHide.getSize().x;
    var fxHide = new Fx.Slide(this.eBar, {
      //wrapper: this.eBar,
      //transition: Fx.Transitions.Pow.easeOut,
      duration: 100,
      onComplete: function() {
        this.hide();
        Ngn.storage.set('hidebleBar'+this.id, false);
      }.bind(this)
    });
    //return;
    var state = Ngn.storage.get('hidebleBar'+this.id);
    if (state === false) {
      fxHide.hide();
      this.hide();
    }
    var fxShow = new Fx.Slide(this.eBar, {
      //transition: Fx.Transitions.Pow.easeOut,
      duration: 100,
      onComplete: function() {
        window.fireEvent('resize');
        Ngn.storage.set('hidebleBar'+this.id, true);
        this.eHandlerShow.setStyle('visibility', 'hidden');
      }.bind(this)
    });
    
    this.eHandlerHide.addEvent('click', function(e){
      e.stop();
      fxHide.slideOut();
    });
    this.eHandlerShow.addEvent('click', function(e){
      e.stop();
      fxShow.slideIn();
    }.bind(this));

  },
  
  hide: function() {
    this.eHandlerShow.setStyle('visibility', 'visible');
    window.fireEvent('resize');
  },
  
  position: function() {
    this.positionHandlerShow();
    Ngn.setToCenterHor(this.eHandlerHide);
    Ngn.setToCenterHor(this.eHandlerShow);
  },
  
  positionHandlerShow: function() {
    if (this.mode == 'down') {
      this.eHandlerShow.setStyle('top', window.getSize().y-this.eHandlerShow.getSize().y);
    } else {
      this.eHandlerShow.setStyle('top', -1);
    }
  }
  
});