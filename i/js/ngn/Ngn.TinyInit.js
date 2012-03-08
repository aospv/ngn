Ngn.TinyInit = new Class({
  Implements: [Options],
  
  options: {
    attachs: false,
    selector: '.type_wisiwig textarea',
    settings: {}
  },

  initialize: function(options) {
    this.setOptions(options);
    this.init();
  },
  
  init: function() {
    var id, settings;
    var parent = this.options.parent ? this.options.parent : document;
    parent.getElements(this.options.selector).each(function(element, n) {
      Ngn.TinyInit.n++;
      id = element.getProperty('id');
      if (!id) id = 'default' + n;
      var suffix = 0;
      if (Ngn.TinyInit.exists[id]) suffix = Ngn.TinyInit.exists[id] + 1;
      var realId = id + suffix;
      element.setProperty('id', realId);
      settings = {elements: realId};
      if (this.options.attachs)
        settings.attachId = this.options.attachIdTpl.replace('{fn}', element.get('name'));
      settings = $merge(this.options.settings, settings);
      if (settings.attachId)
        settings.theme_advanced_buttons1_add = 'uploadFile,uploadImage,uploadImagePreview,image';
      tinyMCE.init(settings);
      if (Ngn.TinyInit.exists[id])
        Ngn.TinyInit.exists[id]++;
      else
       	Ngn.TinyInit.exists[id] = 1;
    }.bind(this));
  }
  
});

Ngn.TinyInit.exists = {};
