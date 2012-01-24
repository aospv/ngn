Ngn.ContextMenu = new Class({
  
  Implements: [new Options],

  initialize: function(btn, options) {
    this.setOptions(options);
    if (Ngn.contextMenu && Ngn.contextMenu.eWraper)
      Ngn.contextMenu.eWraper.dispose();
    this.eBtn = btn;
    this.eWrapper = new Element('div', {
    'class': 'wraper',
    'styles': {
      'position': 'absolute',
      'left': '38px',
      'border': '2px solid #CCCCCC',
      'z-index': '9999'
    }});
    
    this.eMenu = new Element('div', {'styles': {
      'background': '#FFFFFF',
      'padding': '10px',
      'width': '100px'
      //'height': '100px',
    }});
    this.eMenu.inject(this.eWrapper);
    this.eBtn.setStyle('position', 'relative');
    this.eWrapper.inject(btn);
    
    if (this.options.buttons) {
      this.options.buttons.each(function(btn) {
        var eBtn = Ngn.iconBtn(btn.title, 'sm-'+btn.cls);
        eBtn.addEvent('click', function() {
          btn.action.bind(this)();
          return false;
        }.bind(this));
        eBtn.inject(this.eWrapper);
      }.bind(this));
    }
    
    this.eWrapper.addEvent('mouseover', function(e) {
      $clear(this.hideId);
    }.bind(this));

    this.eWrapper.addEvent('mouseleave', function(e) {
      this.hideId = this.close.delay(100, this);
    }.bind(this));

    Ngn.contextMenu = this;
    this.init();
    this.show();
  },
  init: function() {},
  show: function() {
    this.eWrapper.setStyle('visibility', 'visible');
  },
  close: function() {
    this.eWrapper.dispose();
  }

});

