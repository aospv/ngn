Ngn.Dialog.Queue.Request = new Class({
  Extends: Ngn.Dialog.Queue,

  initialize: function(firstDialogOptions, options) {
    this.parent([[Ngn.Dialog.RequestForm, firstDialogOptions]], options);
  },
  
  getDialogOptions: function() {
    return $merge(this.parent(), {
      onSubmitSuccess: function(r) {
        if (r.dialog) {
          Ngn.Dialog.queue.add([eval(r.dialog.cls), r.dialog.options]);
        } else if (r.dialogs) {
          for (var i=0; i<r.dialogs.length; i++)
            Ngn.Dialog.queue.add([eval(r.dialogs[i].cls), r.dialogs[i].options]);
        }
      }
    });
  },
  

});
