Ngn.ResizableTextarea = new Class({
  Implements: [Options],
  
  options: {
    handlerClass: 'hHandler',
    resizableElementSelector: 'textarea',
    textareaSelector: 'textarea',
    modifiers: {x: false, y: true},
    size: {x:[50, 300], y:[35, 500]},
    onResizeClass: 'resize',
    onStart: function(current) {},
    onEnd: function(current) {},
    onResize: function(current) {}
  },
  
  initialize: function(el, options) {
    this.setOptions(options);
    el.eResizable = el.getElement(this.options.resizableElementSelector);
    if (!el.eResizable) return;
    
    el.eResizable.setStyle('border-bottom', '0px');
    var storeKey = el.getElement(this.options.textareaSelector).get('name');
    
    var savedWidth = Ngn.storage.get(storeKey+'w');
    var savedHeight = Ngn.storage.get(storeKey+'h');
    
    if (savedHeight) new Fx.Tween(el.eResizable, {
      duration: 'short',
      onComplete: function() { window.fireEvent('resize'); }
    }).start('height', savedHeight+'px');
    if (savedWidth) new Fx.Tween(el.eResizable, {
      duration: 'short',
      onComplete: function() { window.fireEvent('resize'); }
    }).start('width', savedHeight+'px');
    
    el.eResizable.width = el.eResizable.getWidth();
    el.eResizable.height = el.eResizable.getHeight();
    
    if (this.options.modifiers.x) {
      if(this.options.size.x[0] > this.options.size.x[1]) {
        this.options.size.x[0] = this.options.size.x[1];
      }
      if(el.eResizable.width < this.options.size.x[0]) {
        el.eResizable.setStyle('width', this.options.size.x[0]);
        el.eResizable.width = this.options.size.x[0];
      }
      else if(el.eResizable.width > this.options.size.x[1]) {
        el.eResizable.setStyle('width', this.options.size.x[1]);
        el.eResizable.width = this.options.size.x[1];
      }
    }
    if (this.options.modifiers.y) {
      if(this.options.size.y[0] > this.options.size.y[1]) {
        this.options.size.y[0] = this.options.size.y[1];
      }
      if(el.eResizable.height < this.options.size.y[0]) {
        el.eResizable.setStyle('height', this.options.size.y[0]);
        el.eResizable.height = this.options.size.y[0];
      }
      else if(el.eResizable.height > this.options.size.y[1]) {
        el.eResizable.setStyle('height', this.options.size.y[1]);
        el.eResizable.height = this.options.size.y[1];
      }
    }
    
    el.handler = el.getElement('.'+this.options.handlerClass);
    if(el.handler == null) {
      el.handler = new Element('span', {
        'class': this.options.handlerClass
      });
      this.injectHandler(el);
    }
    el.eResizable.setStyles({'resize': 'none'});
    
    el.handler.left = el.eResizable.width - el.handler.getPosition(el).x;
    el.handler.top = el.eResizable.height - el.handler.getPosition(el).y;
    el.handler.pressed = false;

    el.handler.addEvent('mousedown', function(e) {
      if (!(document.uniqueID && document.compatMode && !window.XMLHttpRequest)) {
        document.onselectstart = function() { return false; }
        document.onmousedown = function() { return false; }
      }
      if (Browser.Engine.trident) { el.handler.setCapture() }
      else  {
        document.addEvent('mousemove', function(e) { el.handler.fireEvent('mousemove', e) });
        document.addEvent('mouseup', function() { el.handler.fireEvent('mouseup') });
      }
      el.handler.pressed = true;
      el.handler.x = e.page.x - el.handler.getPosition().x - el.handler.left;
      el.handler.y = e.page.y - el.handler.getPosition().y - el.handler.top;
      el.addClass(this.options.onResizeClass);
      this.options.onStart(el);
    }.bind(this));
    
    el.handler.addEvent('mouseup', function() {
      if (!(document.uniqueID && document.compatMode && !window.XMLHttpRequest)) {
        document.onmousedown = null;
        document.onselectstart = null;
      }
      if (Browser.Engine.trident) { el.handler.releaseCapture(); }
      else  {
        document.removeEvent('mousemove', function(e) { el.handler.fireEvent('mousemove', e) });
        document.removeEvent('mouseup', function() { el.handler.fireEvent('mousemove') });
      }
      el.handler.pressed = false;
      window.fireEvent('resize');
      el.removeClass(this.options.onResizeClass);
      this.options.onEnd(el);
    }.bind(this));
    
    el.handler.addEvent('mousemove', function(e) {
      if (el.handler.pressed) {
        if (this.options.modifiers.x) {
          el.eResizable.newWidth = e.page.x - el.getPosition().x - el.handler.x;
          if (el.eResizable.newWidth < this.options.size.x[1] && el.eResizable.newWidth > this.options.size.x[0])
            el.eResizable.newWidth = el.eResizable.newWidth;
          else if(el.eResizable.newWidth <= this.options.size.x[0]) 
            el.eResizable.newWidth = this.options.size.x[0];
          else el.eResizable.newWidth = this.options.size.x[1];
          el.eResizable.setStyle('width', el.eResizable.newWidth);
          Ngn.storage.set(storeKey+'w', textarea.newWidth);
          el.handler.setStyle('left', el.eResizable.newWidth - el.handler.left - el.getStyle('border-left-width').toInt());
        }
        if (this.options.modifiers.y) {
          c([e.page.y, el.getPosition().y, el.handler.y, el.getStyle('padding-top').toInt(), el.getStyle('padding-bottom').toInt()]);
          //c(e.page.y);
          
          el.eResizable.newHeight = e.page.y - el.getPosition().y - el.handler.y -
            el.getStyle('padding-top').toInt() - el.getStyle('padding-bottom').toInt();
          c(el.eResizable.newHeight);
          if (el.eResizable.newHeight < this.options.size.y[1] && el.eResizable.newHeight > this.options.size.y[0]) {
            el.eResizable.newHeight = el.eResizable.newHeight;
            c('!');
          }
          else if (el.eResizable.newHeight <= this.options.size.y[0])
            el.eResizable.newHeight = this.options.size.y[0];
          else el.eResizable.newHeight = this.options.size.y[1];
          //c(el.eResizable.newHeight);
          el.eResizable.setStyle('height', el.eResizable.newHeight);    // Меняем высоту texarea
          
          Ngn.storage.set(storeKey+'h', el.eResizable.newHeight); // Записываем в storage
          el.handler.setStyle('top', el.eResizable.newHeight - el.handler.top - el.getStyle('border-top-width').toInt());
        }
        this.options.onResize(el);
      }
    }.bind(this));
  },

  injectHandler: function(el) {
    el.handler.inject(el.eResizable, 'after');
  }
  
});
