Ngn.Dialog.Video = new Class({
  
  Extends: Ngn.Dialog,
  
  options: {
    'id': 'video',
    'file': null,
    'preview': null,
    'footer': false,
    'dialogClass': 'dialog dialog-nopadding',
    'bindBuildMessageFunction': true,
    'setMessageDelay': 100,
    'useFx': false // флэш отображается некрасиво с эффектами
  },
  
  initialize: function(options) {
    options.width = options.params.width + 12; // ширина окна
    options.height = options.params.height;
    if (options.flashvars.title)
      options.title = options.flashvars.title;
    this.parent(options);
  },
  
  buildMessage: function(_msg) {
    return Ngn.video(this.options.params, this.options.flashvars);
  }
  
});
