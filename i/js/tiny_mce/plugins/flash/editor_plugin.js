(function() {
  tinymce.create('tinymce.plugins.Flash', {
    init: function(ed, url) {
      var t = this;
      t.editor = ed;
      ed.addCommand('mceFlash', function() {
        // -------------------
        
      });
      ed.addButton('flash', {
        title : 'Вставить флэш',
        cmd : 'mceFlash'
      });
    }
  });
  // Register plugin
  tinymce.PluginManager.add('flash', tinymce.plugins.Flash);
})();