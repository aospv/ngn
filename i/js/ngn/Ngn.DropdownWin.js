Ngn.DropdownWin = new Class({
  
  Implements: [Options, Events],
  
  options: {
    zIndex: 300,
    eParent: null,
    winClass: ''
  },
  
  initialize: function(ePlaceholder, options) {
    this.setOptions(options);
    this.ePlaceholder = ePlaceholder;
    this.eBtn = this.ePlaceholder.getElement('a');
    if (this.options.winClass) this.options.winClass = ' '+this.options.winClass;
    this.eWin = (
      '<div class="dropdownWin'+this.options.winClass+'">'+
        //'<div class="dropdownTitle tools"></div>'+
        '<div class="dropdownBody"></div>'+
      '</div>'
    ).toDOM()[0].inject(document.getElement('body'));
    
    this.eBody = this.eWin.getElement('.dropdownBody');
    this.eTitle = this.eWin.getElement('.dropdownTitle');
    
    if (this.options.width) this.eWin.setStyle('width', this.options.width+'px');
    if (this.options.height) this.eBody.setStyle('height', this.options.height+'px');
    this.eWin.setStyle('z-index', this.options.zIndex);
    
    this.bodyInitHeight = this.eBody.getSize().y;
    
    this.eWin.set('opacity', 0);
    this.eWin.setStyle('display', 'none');
    this.fireEvent('domReady');
    
    var duration = 100;
    
    this.fxBtn = new Fx.Morph(this.eBtn, {
      duration: duration,
      link: 'cancel'
    });
    
    this.fx = new Fx.Morph(this.eWin, {
      duration: duration,
      link: 'cancel',
      onComplete: function(eWin) {
        if (eWin.get('opacity') == 0) {
          eWin.setStyle('display', 'none');
          this.eBtn.removeClass('over');
          this.hiding = false;
        }
        
      }.bind(this),
      onStart: function(eWin) {
        this.initWinPosition();
      }.bind(this)
    });
    this.eBtn.addEvent('click', function() {
      return false;
    });
    this.eBtn.addEvent('mouseover', function() {
      if (this.disabled) return;
      this.cancelHide();
      if (this.opened) return;
      this.initWinPosition();
      this.opened = true;
      this.eWin.setStyle('visibility', 'visible');
      if (this.options.eParent)
        this.eWin.setStyle('z-index', this.options.eParent.getStyle('z-index'));
      this.eBtn.addClass('over');
      this.eWin.setStyle('display', 'block');
      if (this.eWin.get('opacity') != 1) {
        this.fx.start({'opacity': [0, 1]});
        // окно не открывается
        this.fxBtn.start({
          'border-color': ['#FFFFFF', '#CCCCCC'],
          'background-color': ['#FFFFFF', '#FFEB8F']
        });
      }
    }.bind(this));
    this.eBtn.addEvent('mouseout', function() {
      this.startHide();
    }.bind(this));
    this.eWin.addEvent('mouseover', function() {
      this.cancelHide();
    }.bind(this));
    this.eWin.addEvent('mouseout', function() {
      this.startHide();
    }.bind(this));
    window.addEvent('dialogMove', this.initWinPosition.bind(this));
  },
  
  opened: false,
  
  hiding: false,
  
  startHide: function() {
    if (!this.opened) return;
    if (this.hiding) return;
    this.hiding = true;
    this.hideId = (function() {
      this.fx.start({'opacity': [1, 0]});
      this.fxBtn.start({
        'border-color': ['#CCCCCC', '#FFFFFF'],
        'background-color': ['#FFEB8F', '#FFFFFF']
      });
      this.opened = false;
    }).delay(100, this);
  },
  
  cancelHide: function() {
    if (!this.hideId) return;
    this.hiding = false;
    $clear(this.hideId);
  },
  
  initWinPosition: function() {
    var btnPos = this.eBtn.getPosition();
    var btnSize = this.eBtn.getSize();
    var dwinSize = this.eWin.getSize();
    var btnBottomY = btnPos.y + btnSize.y;
    var btnRightX = btnPos.x + btnSize.x;
    var winSize = window.getSize();
    if (btnBottomY + dwinSize.y > winSize.y) {
      var winTop = btnPos.y - dwinSize.y + 1;
    } else {
      var winTop = btnPos.y + btnSize.y - 1;
    }
    if (btnRightX + dwinSize.x > winSize.x) {
      var winLeft = btnPos.x - dwinSize.x + btnSize.x;
    } else {
      var winLeft = btnPos.x;
    }
    this.eWin.setStyles({
      left: winLeft,
      top: winTop
    });
  }
  
});