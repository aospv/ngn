Ngn.getAttacheTempUrl = function(fieldName) {
  return '/c2/fancyUpload?tempId='+Ngn.sessionId+'&fn='+fieldName;
};

Ngn.uploadAttache = function(list, attachs, _options) {
  
  if (_options.fileType) {
    if (_options.fileType == 'image') {
      _options.typeFilter = {
        'Изображения (*.jpg, *.jpeg, *.gif, *.png)':
          '*.jpg; *.jpeg; *.gif; *.png'
      };
    } else if (_options.fileType == 'video') {
      _options.typeFilter = {
        'Видео (*.mpg, *.mpeg, *.mp4, *.wmv, *.avi, *.flv, *.mov, *.mov, *.3gp)':
          '(*.mpg; *.mpeg; *.mp4; *.wmv; *.avi; *.flv; *.mov; *.3gp)'
      };
    }
  }
  
  var options = {
    path :'/i/js/fancy/Swiff.Uploader.swf',
    fileSizeMax: Ngn.fileSizeMax,
    verbose: false,
    queued: false,
    multiple: false,
    allowDuplicates: true,
    appendCookieData: true,
    
    defaultSelectDisplay: 'block',

    onSelectFail: function(files) {
      files.each(function(file) {
        new Element('li', {
          'class': 'file-invalid',
          events: {
            'click': function() {
              this.destroy();
            }
          }
        }).adopt(new Element('span', {
          html: file.validationErrorMessage || file.validationError
        })).inject(this.list, 'bottom');
      }, this);
    },

    onFileSuccess: function(file) {
      if (!this.options.multiple) {
        if (this.loadedFilesUi && this.loadedFilesUi.length) {
          for (var i=0; i<this.loadedFilesUi.length; i++) {
            this.loadedFilesUi[i].element.dispose();
            this.loadedFilesUi.erase(this.loadedFilesUi[i]);
          }
        }
        if (file.base.fileList.length > 1) {
          file.base.fileList[0].ui.element.destroy();
          file.base.fileList.erase(file.base.fileList[0]);
        }
      }
      /*
      new Element('input', {
        type: 'checkbox',
        'checked': true
      }).inject(file.ui.element, 'top');
      */
      //file.ui.element.highlight('#e6efc2');
    },

    onFileError: function(file) {
      file.ui.cancel.set('html', 'Retry').removeEvents().addEvent('click',
        function() {
          file.requeue();
          return false;
        });
      new Element('span', {
        html: file.errorMessage,
        'class': 'file-error'
      }).inject(file.ui.cancel, 'after');
    },

    onFileRequeue: function(file) {
      file.ui.element.getElement('.file-error').destroy();
      file.ui.cancel.set('html', 'Отмена').removeEvents().addEvent('click',
        function() {
          file.remove();
          return false;
        });
      this.start();
    }
  }
  $extend(options, _options);
  
  options.url = Ngn.addUrlParam(options.url, 'multiple', options.multiple ? 1 : 0);
  
  var up = new FancyUpload3.Attach(list, attachs, options);

  if (options.loadedFiles) {
    var ui = [];
    for (var i=0; i<options.loadedFiles.length; i++) {
      var data = options.loadedFiles[i];
      if (!data.id) data.id = 'none';
      ui[i] = {};
      ui[i].element = new Element('li', {'class': 'file', id: 'file-' + data.id});
      ui[i].title = new Element('span', {'class': 'file-title', html: '<span class="gray">Загружен:</span>' + data.name});
      ui[i].size = new Element('span', {'class': 'file-size', text: Swiff.Uploader.formatUnit(data.size, 'b')});
      ui[i].element.adopt(
        ui[i].title,
        ui[i].size
      ).inject(up.list);
    }
    up.loadedFilesUi = ui;
  }
}