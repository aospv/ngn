(function() {
  tinymce.create('tinymce.plugins.ImageUploader', {
    init: function(ed, url) {
      var t = this;
      t.editor = ed;
      
      // --------------------------------------------------------------------------------
      ed.addCommand('mceUploadImage', function() {
        var insertImage = function(imageUrl) {
          bookmark = tinyMCE.activeEditor.selection.getBookmark();
          tinyMCE.activeEditor.execCommand(
          'mceInsertContent', false, tinyMCE.activeEditor.dom.createHTML('img', {
            src: imageUrl,
            border: 0
          }));
          tinyMCE.activeEditor.selection.moveToBookmark(bookmark);
        };
        new Ngn.Dialog.RequestForm({
          url: '/c/tinyImageUploader' +
            '?tinyAttachId=' + ed.settings.attachId,
          onSubmitSuccess: function(r) {
            insertImage(r.imageUrl);
          }
        });
      });
      ed.addButton('uploadImage',
        {title: 'Вставить изображение', cmd: 'mceUploadImage'});
      
      // --------------------------------------------------------------------------------
      ed.addCommand('mceUploadImagePreview', function() {
        new Ngn.Dialog.RequestForm({
          url: '/c/tinyImagePreviewUploader' +
            '?tinyAttachId=' + ed.settings.attachId,
          onSubmitSuccess: function(r) {
            tinyMCE.activeEditor.execCommand(
              'mceInsertContent', false,
              '<a href="'+r.imageUrl+'" target="_blank" class="iiLink '+
                't'+(r.resizeType.capitalize())+'">'+
                '<img src="'+r.smImageUrl+'"></a>'
            );
          }
        });
      });
      ed.addButton('uploadImagePreview',
        {title: 'Вставить изображение с предпросмотром', cmd: 'mceUploadImagePreview'});
      
      // --------------------------------------------------------------------------------
      ed.addCommand('mceImageProp', function() {
        var ed, el, args;
        ed = tinyMCE.activeEditor;
        el = ed.selection.getNode();
        var updateOrInsert = function(data) {
          args = {
            alt: data.alt,
            title: data.alt
          };
          if (el && el.nodeName == 'IMG') {
            ed.dom.setAttribs(el, args);
          } else {
            ed.execCommand('mceInsertContent', false, '<img id="__mce_tmp" />', {skip_undo: 1});
            ed.dom.setAttribs('__mce_tmp', args);
            ed.dom.setAttrib('__mce_tmp', 'id', '');
            ed.undoManager.add();
          }
        };
        var dialog = new Ngn.Dialog.Form({
          url: '/c/tinyImageProp',
          onFormInit: function() {
            Ngn.frm.objTo(this.form.eForm, $(el).getProperties('alt'));
          },
          onSubmit: function(data) {
            updateOrInsert(data);
          }
        });
      });
      ed.addButton('image',
        {title: 'Параметры изображения', cmd: 'mceImageProp'});
    },

    getInfo: function() {
      return {
        longname: 'Вставить изображение',
        author: 'masted'
      };
    }

  });

  // Register plugin
  tinymce.PluginManager.add('imageuploader', tinymce.plugins.ImageUploader);
})();