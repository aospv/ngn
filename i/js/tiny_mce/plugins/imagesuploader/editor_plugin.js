/**
 * $Id: editor_plugin_src.js 520 2008-01-07 16:30:32Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
  tinymce.create('tinymce.plugins.ImagesUploader', {
    init : function(ed, url) {
      var t = this;
      t.editor = ed;
      ed.addCommand('mceUploadImages', function() {
        ed.windowManager.open({
          file: './c/tinyImagesUploader' +
            (ed.settings.attachId ? '?attachId=' + ed.settings.attachId : ''),
          width: 270,
          height: 200,
          inline: 1
        }, {
          plugin_url : url
        });
      });
      ed.addButton('uploadImages', {
        title: 'Вставить несколько изображений сразу',
        cmd: 'mceUploadImages'
      });
    },

    getInfo : function() {
      return {
        longname : 'Вставить несколько изображений сразу',
        author : 'masted'
      };
    }

  });

  // Register plugin
  tinymce.PluginManager.add('imagesuploader', tinymce.plugins.ImagesUploader);
})();