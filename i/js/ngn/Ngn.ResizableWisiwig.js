Ngn.ResizableWisiwig = new Class({
  
  Extends: Ngn.ResizableTextarea,
  
  options: {
    resizableElementSelector: 'iframe'
  },
  
  injectHandler: function(el) {
    el.handler.inject(el.eResizable.getParent().getParent().getParent().getParent().getParent(), "after");
  }
  
});