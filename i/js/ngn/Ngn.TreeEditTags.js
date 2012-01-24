Ngn.TreeEditTags = new Class({
  
  Extends: Ngn.TreeEdit,
  
  options: {
    id: 'tags',
    enableStorage: false,
    actionUrl: '/admin/tags',
    folderOpenIcon: 'mif-tree-tag-open-icon',
    folderCloseIcon: 'mif-tree-tag-close-icon',
    pageIcon: 'mif-tree-tag-close-icon'
  },
  
  initUrl: function() {
    this.url = this.options.actionUrl + '/' + this.groupId;
  },
  
  initialize: function(container, groupId, options) {
    this.groupId = groupId;
    this.parent(container, options);
  },
  
  toggleButtons: function() {
    this.parent();
    this.toggleButton('add', true);
    this.tree.addEvent('selectChange', function(node) {
      if (!node.tree.selected) {
        for (var name in this.buttons) {
          if (name != 'add')
            this.toggleButton(name, false);
        }
        return;
      }
      this.toggleButton('rename', true);
      this.toggleButton('delete', true);
    }.bind(this));
  }
  
});
