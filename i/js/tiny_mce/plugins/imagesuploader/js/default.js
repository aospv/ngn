var ImagesUploaderDialog = {
  
  init : function(ed) {
    tinyMCEPopup.resizeToInnerSize();
  },

  insertImage : function(imageUrl) {
    var ed = tinyMCEPopup.editor, dom = ed.dom;
    tinyMCEPopup.execCommand('mceInsertContent', false, dom.createHTML('img', {
      src : imageUrl,
      border : 0
    }));
  }
  
};

tinyMCEPopup.onInit.add(ImagesUploaderDialog.init, ImagesUploaderDialog);
