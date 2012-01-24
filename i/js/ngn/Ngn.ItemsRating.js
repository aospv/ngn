Ngn.ItemsRating = new Class({

  Implements: [Options],
  
  options: {
    itemsSelector: '.ddRating'
  },
  
  initialize: function(options) {
    this.setOptions(options);
    $$(this.options.itemsSelector).each(function(el) {
      new Ngn.ItemRating(el, options);
    });
  }
  
});