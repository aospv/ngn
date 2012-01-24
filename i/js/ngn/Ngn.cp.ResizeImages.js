Ngn.cp.ResizeImages = new Class({

  initialize: function(onComplete) {
    this.eSmW = $('smWi');
    if (!this.eSmW) return;
    this.eSmH = $('smHi');
    this.eMdW = $('mdWi');
    this.eMdH = $('mdHi');
    this.initSmW = this.eSmW.get('value');
    this.initSmH = this.eSmH.get('value');
    this.initMdW = this.eMdW.get('value');
    this.initMdH = this.eMdH.get('value');
    this.onComplete = onComplete;
  },

  smImagesChanged: function() {
    return (this.eSmW.get('value') != this.initSmW || this.eSmH.get('value') != this.initSmH); 
  },
  
  mdImagesChanged: function() {
    return (this.eMdW.get('value') != this.initMdW || this.eMdH.get('value') != this.initMdH); 
  },

  /*
  type: sm/md
  onComplete: function
  */
  resize: function(type, onComplete) {
    //new Fx.Scroll(window).toElement($(type + 'Wi'));
    new Fx.Scroll(window).toTop();
    new Ngn.PartialJob(
      Ngn.getPath() + '?a=json_resize' + type.capitalize() + 'Images',
      {
        loaderTitleComplete: 'Готово. Сохраняем форму',
        loaderTitleStart: 'Происходит изменение размеров ' +
          (type == 'sm' ? 'превьюшек' : 'уменьшенных копий') + '. Подождите',
        onComplete: onComplete,
        requestParams: {
          w: $(type + 'Wi').get('value'),
          h: $(type + 'Hi').get('value')
        }
      }
    ).start();
  },

  formValidation: function() {
    var smChanged = this.smImagesChanged();
    var mdChanged = this.mdImagesChanged();
    if (smChanged || mdChanged) {
      if (smChanged) {
        this.resize('sm', function() {
          if (mdChanged) {
            this.resize('md', function() {
              eForm.submit();
            }.bind(this));
          } else {
            eForm.submit();
          }
        }.bind(this));
      } else if (mdChanged) {
        this.resize('md', function() {
          if (smChanged) {
            this.resize('sm', function() {
              this.onComplete();
            }.bind(this));
          } else {
            this.onComplete();
          }
        }.bind(this));
      }
      return false;
    }
    return true;
  }
  
});
