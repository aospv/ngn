Ngn.TinyInit = new Class({
  Implements: [Options],
  
  options: {
    attachs: false,
    selector: '.type_wisiwig textarea',
    settings: Ngn.TinySettings
  },

  initialize: function(options) {
    this.setOptions(options);
    this.init();
  },
  
  init: function() {
    var id, settings;
    this.settings = new this.options.settings;
    var parent = this.options.parent ? this.options.parent : document;
    parent.getElements(this.options.selector).each(function(element, n) {
      Ngn.TinyInit.n++;
      //var id = 'textarea'+Ngn.TinyInit.n;
      //c(id);
      //element.setProperty('id', id);
      
      id = element.getProperty('id');
      if (!id) {
        id = 'textarea' + n;
        element.setProperty('id', id);
      }
      
      //tinyMCE.execCommand('mceAddControl', true, id);
      
      //c(id);
      //if (Ngn.TinyInit.exists[id]) {
        //c('!');
        //tinymce.execCommand('mceAddControl', true, id);
        //return;
      //}
      //c(Ngn.TinyInit.exists);
      
      settings = {elements: id};
      if (this.options.attachs)
        settings.attachId = this.options.attachIdTpl.replace('{fn}', element.get('name'));
      settings = $merge(this.settings.getSettings(), settings);
      //if (!this.options.attachs)
        //settings.theme_advanced_buttons2_add_before = 'blockquote';
      if (settings.attachId)
        settings.theme_advanced_buttons1_add = 'uploadFile,uploadImage,uploadImagePreview,image';
      tinyMCE.init(settings);
    }.bind(this));
  }
  
});

Ngn.TinyInit.exists = {};
