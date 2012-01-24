/**
 * $Id: editor_plugin_src.js 520 2008-01-07 16:30:32Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
  tinymce.create('tinymce.plugins.FileUploader', {
    init: function(ed, url) {
      var t = this;
      t.editor = ed;
      ed.addCommand('mceUploadFile', function() {
        var insertFile = function(url, title, filesize) {
          tinyMCE.activeEditor.execCommand(
            'mceInsertContent', false,
            '<a href="'+url+'" target="_blank" class="ifLink">' + title +
              ' ('+Ngn.filesizeFormat(filesize)+')</a>'
          );
        };
        new Ngn.Dialog.RequestForm({
          url: '/c/tinyFileUploader' +
            '?tinyAttachId=' + ed.settings.attachId,
          onSubmitSuccess: function(r) {
            insertFile(r.url, r.title, r.filesize);
          }
        });
      });
      ed.addButton('uploadFile', {title: 'Вставить файл', cmd: 'mceUploadFile'});
    },

    getInfo: function() {
      return {
        longname: 'Вставить файл',
        author: 'masted'
      };
    }

  });

  // Register plugin
  tinymce.PluginManager.add('fileuploader', tinymce.plugins.FileUploader);
})();