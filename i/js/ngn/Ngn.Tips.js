Ngn.Tips = new Class({
  Extends: Tips,
  
  toElement: function() {
    // by masted
    var lastTip = document.getElement('.'+this.options.className);
    if (lastTip) lastTip.dispose();
    // --------------
    return this.parent();
  }

});