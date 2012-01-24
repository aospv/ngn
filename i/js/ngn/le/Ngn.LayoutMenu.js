Ngn.LayoutMenu = new Class({

  initialize: function(layoutEditor, eMenu) {
    this.layoutEditor = layoutEditor;
    this.eMenu = eMenu;
    this.eHandler = new Element('div', {'class': 'leHandler'});
    this.eHandler.inject(this.eMenu, 'top');
    
    new Drag.Move(this.eMenu, {
      container: this.layoutEditor.eLayout,
      handle: this.eHandler
    });
  }

});