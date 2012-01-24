Ngn.ContextMenuPages = new Class({
  
  Extends: Ngn.ContextMenu,
  
  options: {
    buttons: [
      {
        title: 'Переименовать',
        cls: 'rename',
        action: function() {
          if (!this.tree.selected.parentNode) return; // значит это дерево, а не нод
          if (this.tree.selected) {
            this.tree.selected.rename();
          }
        }
      },
    ]
  },
  
  initialize: function(btn, tree, options) {
    this.tree = tree;
    this.parent(btn, options);
  } 
  
});