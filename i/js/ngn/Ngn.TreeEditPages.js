Ngn.TreeEditPages = new Class({
  
  Extends: Ngn.TreeEdit,
  
  options: {
    id: 'tePages',
    enableLocalStorage: false,
    actionUrl: '/admin/pages'
  },
  
  initialize: function(twoScreens, container, options) {
    this.twoScreens = twoScreens;
    this.parent(container, options);
  },
  
  add: function() {
    this.twoScreens.openModuleCreateDialog(this.tree.selected.data.id);
  },
  
  createPage: function() {
    this.twoScreens.openCreateDialog(this.tree.selected.data.id);
  },
  
  getMifTreeOptions: function() {
    var options = this.parent();
    if (!this.options.disableDragging) {
      options.initialize = function() {
        new Mif.Tree.Drag(this);
      };
    } else {
      options.initialize = function() {};
    }
    options.types.page.dropDenied = ['inside'];
    return options;
  },
  
  bindButtons: function() {
    if (!this.parent()) return;
    
    this.createButton('editContent', this.eButtons.getElement('a[class~=edit]'), function(){
      window.location = Ngn.getPath(1) + '/pages/' + this.tree.selected.data.id + 
        '/editContent';
    }.bind(this));
    
    this.createButton('openFolder', this.eButtons.getElement('a[class~=openFolder]'), function(){
      window.location = Ngn.getPath(1) + '/pages/' + this.tree.selected.data.id;
    }.bind(this));
    
    this.createButton('createPage', this.eButtons.getElement('a[class~=add2]'),
      this.createPage.bind(this));
    
    this.createButton('editProp', this.eButtons.getElement('a[class~=editProp]'), function() {
      this.twoScreens.openPagePropDialog(this.tree.selected.data.id)
    }.bind(this));
    
    this.createButton('controllerSettings',
      this.eButtons.getElement('a[class~=editOptions]'),
      function() {
        this.twoScreens.openControllerSettingsDialog(this.tree.selected.data.id);
      }.bind(this)
    );
    
    this.createButton('openPage', this.eButtons.getElement('a[class~=link]'), function() {
      window.open(this.tree.selected.data.path);
    }.bind(this));
    
    this.createButton('pageBlocks', this.eButtons.getElement('a[class~=pageBlocks]'), function() {
      window.location = Ngn.getPath(1) + '/pageBlocks/' + this.tree.selected.data.id;
    }.bind(this));
    
    this.createButton('layout', this.eButtons.getElement('a[class~=layout]'), function() {
      window.location = Ngn.getPath(1) + '/pageLayout/' + this.tree.selected.data.id;
    }.bind(this));
    
    this.createButton('ddOutput', this.eButtons.getElement('a[class~=ddOutput]'), function() {
      window.location = Ngn.getPath(1) + '/ddOutput/' + this.tree.selected.data.id;
    }.bind(this));
    
    this.createButton('meta', this.eButtons.getElement('a[class~=meta]'), function() {
      new Ngn.Dialog.RequestForm({
        url: Ngn.getPath(1) + '/pageMeta/' + this.tree.selected.data.id
      });
    }.bind(this));
    
    this.createButton('privileges', this.eButtons.getElement('a[class~=privileges]'), function() {
      //c(Ngn.getPath(1) + '/privileges/' + this.tree.selected.data.id + '/pagePrivileges');
      new Ngn.Dialog.RequestForm({
        url: Ngn.getPath(1) + '/pagePriv/' + this.tree.selected.data.id + '/json_editPage'
      });
      //window.location = Ngn.getPath(1) + '/privileges/' + this.tree.selected.data.id + '/pagePrivileges';
    }.bind(this));
    
  },
  
  getActiveButtonNames: function() {
    return this.parent().extend(['openPage']);
  },
  
  toggleButtons: function() {
    this.parent();
    this.tree.addEvent('selectChange', function(node) {
      if (!node.tree.selected) {
        for (var name in this.buttons)
          this.toggleButton(name, false);
        return;
      }
      this.toggleButton('add', node.data.folder == 1);
      this.toggleButton('createPage', node.data.folder == 1);
      this.toggleButton('openFolder', node.data.folder == 1);
      this.toggleButton('editContent', node.data.editableContent);
      this.toggleButton('controllerSettings', true);
      this.toggleButton('editProp', true);
      this.toggleButton('meta', true);
      this.toggleButton('pageBlocks', true);
      this.toggleButton('layout', true);
      this.toggleButton('privileges', true);
      this.toggleButton('ddOutput', node.data.dd);
    }.bind(this));
  }
  
});