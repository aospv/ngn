Ngn.AdminPanel = new Class({
  
  Implements: Options,
  
  options: {
    rightOffset: 35
  },
  
  initialize: function(ePanel, options) {
    this.ePanel = $(ePanel);
    if (!this.ePanel) return; 
    this.setOptions(options);
    this.init();
  },
  
  init: function() {
    var panelRightOffset = this.ePanel.getScrollSize().x + this.options.rightOffset;
    var devPanelX = Cookie.read('devPanelX');
    if (devPanelX) {
      this.ePanel.setStyle('left', devPanelX.toInt());
      this.ePanel.setStyle('top', Cookie.read('devPanelY').toInt());
    } else {
      this.ePanel.setStyle('left', window.getScrollSize().x - panelRightOffset);
    }
    
    window.addEvent('resize', function() {
      this.ePanel.setStyle('left', window.getScrollSize().x - panelRightOffset);
      this.ePanel.setStyle('width', 'auto');
    }.bind(this));
    
    new Drag.Move(this.ePanel, {
      handle: this.ePanel.getElement('.adminPanelDrag'),
      onDrop: function(element, droppable){
        Cookie.write('devPanelX', element.getPosition().x);
        Cookie.write('devPanelY', element.getPosition().y);
      }
    }); 
  }
  
});

Ngn.DevPanel = new Class({
  Extends: Ngn.AdminPanel,
  init: function() {
    this.parent();
    $('sqlDataOpen').addEvent('click', function(e){
      $('sqlData').setStyle('display', 'block');
      this.ePanel.setStyle('left', 20);
      this.ePanel.setStyle('top', 20);
      this.ePanel.setStyle('width', 'auto');
      return false;
    }.bind(this));
    $('sqlDataToggleBacktrace').addEvent('click', function(e){
      new Event(e).stop();
      var display = ($('sqlData').getElement('.backtrace').getStyle('display') == 'block') ?
        'none' : 'block';
      $('sqlData').getElements('.backtrace').each(function(el){
        el.setStyle('display', display);
      });
      return false;
    });
  }
});

Ngn.GodPanel = new Class({
  Extends: Ngn.AdminPanel
});
