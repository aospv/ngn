Ngn.Dialog.Tiny = new Class({
  Extends: Ngn.Dialog,
  
  initialize: function(_opts) {
    _opts.dialogClass = 'dialog dialog-tiny';
    var opts = $merge(_opts, {
      'ok': this.closeAction.bind(this),
      'bindBuildMessageFunction': true
    });
    this.parent(opts);
    this.message.setStyle('padding', '0'); // Убираем отступы
  },
  
  buildMessage: function(_msg) {
    this.id = 'wisiwig_' + this.options.id;
    this.eTextarea = new Element('textarea', {
      'id': this.id,
      'text': _msg
    });
    this.eTextarea.setStyle('width', '100%'); // нужно выставлять ширину именно яваскриптом
    (function(){
      tinyMCE.init($merge(new Ngn.TinySettings().getSettings(), {
        'elements': this.id,
        'attachId': this.id
      }));
    }).delay(100, this);
    return this.eTextarea;
  },
  
  closeAction: function(_canceled) {
    this.options.callback(tinyMCE.get(this.id).getContent());
  }
  
});
