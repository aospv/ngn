Ngn.BlockEditDialog = {};
Ngn.BlockEditDialog.Dynamic = new Class({
  requiredOptions: ['blockId'],
  updateBlock: function() {
    var eCont = $('block_'+this.options.blockId).getElement('.bcont');
    eCont.set('load', {evalScripts: true}).load('/pageBlock/ajax_get/'+this.options.blockId);
  }
});
Ngn.BlockEditDialog.Static = new Class({
  requiredOptions: ['className', 'type'],
  updateBlock: function() {
    var eCont = document.body.getElement('.pbt_'+this.options.type).getElement('.bcont');
    eCont.set('load', {evalScripts: true}).load(
      '/pageBlock/ajax_get2/'+this.options.className+'/'+this.options.type);
  }
});