Ngn.iconBtn = function(title, btnClass, properties) {
  var a = new Element('a', $merge({
    'class': 'smIcons ' + 'sm-'+btnClass,
    'html': title
  }, properties || {}));
  new Element('i').inject(a, 'top');
  return a;
};

Ngn.IconBtn = new Class({
  
  initialize: function(el, action) {
    this.el = el;
    this.toggle(true);
    this.el.addEvent('click', function(e) {
      e.preventDefault();
      if (!this.enable) return;
      action(this);
    }.bind(this));
    this.init();
  },
  
  init: function() {},
  
  toggle: function(enable) {
    this.enable = enable;
    enable ? this.el.removeClass('nonActive') : this.el.addClass('nonActive');
  }
  
});
