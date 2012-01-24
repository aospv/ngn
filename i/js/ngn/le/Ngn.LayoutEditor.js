Ngn.LayoutEditor = new Class({
  
  Implements: Options,

  initialize: function(options) {
    this.eLayout = $('layout');
    this.setOptions(options);
    new Ngn.LayoutMenu(this, $('menu'));
  }

});