Ngn.TreeStateStorage = new Class({

  Implements: [Options],
  
  options:{
    store: function(node){
      return node.property.id;
    },
    retrieve: function(value){
      return Mif.id(value);
    }
  },

  initialize: function(tree, options){
    this.setOptions(options);
    this.tree = tree;
    this.nodes = [];
    this.initToggleStore();
    this.tree.addEvent('selectChange', function(node) {
      Ngn.storage.set(this.tree.id + 'selected', this.options.store(node));
    }.bind(this));
  },

  write: function(){
    //c(['w', this.tree.id+'toggle', this.nodes]);
    this.restored = this.nodes;
    Ngn.storage.json.set(this.tree.id+'toggle', this.nodes);
  },
  
  read: function(){
    //c(['r', this.tree.id+'toggle', Ngn.storage.json.get(this.tree.id+'toggle')]);
    return Ngn.storage.json.get(this.tree.id+'toggle') || [];
  },
  
  restore: function(data){
    // toggle restore
    if (!data) this.restored = this.restored || this.read();
    var restored = data || this.restored;
    for (var i = 0; i < restored.length; i++) {
      var stored = restored[i];
      //c('stored: '+stored);
      var node = this.options.retrieve(stored);
      if (node) {
        node.toggle(true);
      }
    }
    // select restore
    var selected = Ngn.storage.get(this.tree.id + 'selected');
    if (selected) {
      var node = this.options.retrieve(selected);
      if (node) {
        this.tree.select(node, false);
      }
    }
  },
  
  initToggleStore: function(){
    this.tree.addEvent('toggle', function(node, state){
      var value = this.options.store(node);
      if (state) {
        this.nodes.include(value);
      } else {
        this.nodes.erase(value);
      }
      this.write();
    }.bind(this));
  }

});
