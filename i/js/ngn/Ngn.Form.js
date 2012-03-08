Form.Validator.addAllThese([
  ['should-be-changed', {
    errorMsg: 'значение этого поля должно быть изменено',
    test: function(element) {
      if (Ngn.Form.forms[element.getParent('form').get('id')].
      initValues[element.get('name')] == element.get('value'))
        return false;
      else
        return true;
    }
  }],
  ['validate-name', {
    errorMsg: 'должно содержать только латинские символы и не начинаться с цифры',
    test: function(element) {
      if (!element.value) return true;
      if (element.value.match(/^[a-z][a-z0-9-_]*$/i)) return true;
      else return false;
    }
  }],
  ['validate-domain', {
    errorMsg: 'неправильный формат',
    test: function(element) {
      if (!element.value) return true;
      if (element.value.match(/^[a-z][a-z0-9-.]*[a-z]$/i)) return true;
      else return false;
    }
  }],
  ['validate-phone', {
    errorMsg: 'неправильный формат',
    test: function(element) {
      if (!element.value) return true;
      element.value = element.value.replace(/[\s-()]/g, '');
      return true;
    }
  }],
  ['validate-skype', {
    errorMsg: 'неправильный формат',
    test: function(element) {
      if (!element.value) return true;
      if (element.value.length > 32 || element.value.length < 6) return false;
      if (element.value.match(/^[a-z][a-z0-9._]*$/i)) return true;
      else return false;
    }
  }],
  ['required-wisiwig', {
    errorMsg: 'поле обязательно для заполнения',
    test: function(element) {
      if (!Ngn.clearParagraphs(tinyMCE.get(element.get('id')).getContent()))
        return false;
      return true;
    }
  }],
  ['validate-fancyUpload', {
    errorMsg: 'Файл ещё не загружен',
    test: function(element) {
      if (element.get('value') == 'uploading') return false; 
      return true;
    }
  }],
  ['validate-fancyUpload-required', {
    errorMsg: 'Файл не загружен',
    test: function(element) {
      if (element.get('value') == 'complete') return true; 
      return false;
    }
  }]
]);

Ngn.Form = new Class({
  Implements: [Options, Events, Class.Occlude],
  
  options: {
    equalElementHeights: false,
    dialog: false,
    focusFirst: true
    //onComplete: null
  },
  
  initialize: function(eForm, options) {
    this.eForm = $(eForm);
    if (this.occlude(this.eForm.get('id'), this.eForm)) return this.occluded;
    
    Ngn.Form.forms[this.eForm.get('id')] = this;
    this.setOptions(options);
    
    //this.initAutoGrow(); // не работает так, как ожидается =(
    this.initValidator();
    
    //(function(){
      // new Ngn.TinyInitSimple({parent: this.eForm});
    //}).delay(100, this);
    
    this.initMooRainbow();
    this.initInlineJs();
    this.initVisibilityConditions();
    this.initHeaderToggle();
    this.initFileNav();
    this.initActive();
    this.initCols();
    this.initImagePreview();
    if (this.options.focusFirst) {
    	var f = this.eForm.getElements(Ngn.frm.textSelector);
    	if (f[0]) f[0].focus();
    }
    // this.initIframeUpload();
    // this.initSubmit();
    
  },
  
  initImagePreview: function() {
    this.eForm.getElements('.elImagePreview').each(function(el){
      eFileNav = el.getElement('.fileNav');
      if (!eFileNav) return;
      eFileNav.inject(el.getElement('.label'), 'top');
    });
    Ngn.lightbox.add(this.eForm.getElements('a.lightbox'));
  },
  
  initActive: function() {
    this.eForm.getElements('textarea,input[type=text]').each(function(el){
      el.addEvent('focus', function() {
        this.addClass('active');
      });
      el.addEvent('blur', function() {
        this.removeClass('active');
      });
    });
  },
  
  initCols: function() {
    var cols = this.eForm.getElements('.type_col');
    for (var i=0; i<cols.length; i++) {
      var children = cols[i].getChildren();
      var eColBody = new Element('div', {'class': 'colBody'}).inject(cols[i]);
      for (var j=0; j<children.length; j++)
        children[j].inject(eColBody);
    }
  },
  
  initSubmit: function() {
    this.btnSubmit = this.eForm.getElement('input[type=submit]');
    if (!this.btnSubmit) return;
    this.eForm.addEvent('submit', function(e){
      //e.preventDefault();
      if (this.submiting) return;
      if (this.validator.validate()) {
        this.submiting = true;
        this.btnSubmit.addClass('loading');
        Ngn.frm.disable(this.eForm, true);
        this.eForm.submit();
      }
    }.bind(this));
  },
  
  isFancyUpload: false,
  
  initUpload: function(opt) {
    if (Browser.Plugins.Flash.version && opt && opt.url) {
      this.isFancyUpload = true;
      this.initFancyUpload(opt);
    } else if (this.options.dialog && this.hasFilesFields()) {
      this.initIframeRequest(opt);
    }
  },
  
  hasFilesFields: function() {
    return this.eForm.getElements('input[type=file]').length != 0;
  },
  
  initHeaderToggle: function() {
    var htBtns = this.eForm.getElements('.type_headerToggle input[type=button]');
    var ht = [];
    if (htBtns) {
      for (var i=0; i<htBtns.length; i++)
        ht.push(new Ngn.frm.HeaderToggle(htBtns[i]));
    }
    if (this.options.equalElementHeights) {
      this.setEqualHeights();
      for (i=0; i<ht.length; i++)
        ht[i].addEvent('toggle', function(open) {
          if (open) this.setEqualHeights();
        }.bind(this));
    }
  },
  
  visibilityConditions: [],
  
  setEqualHeights: function() {
    this.eForm.getElements('.hgrp').each(function(eHgrp){
      Ngn.equalItemHeights(eHgrp.getElements('.element').filter(function(el){
        return !el.hasClass('subElement');
      }));
    });
  },
  
  initVisibilityConditions: function() {
    var vc = this.eForm.getElement('.visibilityConditions');
    if (!vc) return;
    vc = JSON.decode(vc.get('html'));
    for (var i=0; i<vc.length; i++) {
      this.visibilityConditions[vc[i][0]] = new Ngn.frm.VisibilityCondition(
        this.eForm,
        vc[i][0],
        vc[i][1],
        vc[i][2]
      );
    }
  },
  
  initInlineJs: function() {
    var eForm = this.eForm;
    var js = $(this.eForm.get('id')+'js');
    if (js) {
      try {
        eval(js.get('html'));
      } catch (e) {
        //c(e);
        throw new Error('Error in code: ' + js.get('html') + "\nerror:" + e.toString());
      }
    }
  },
  
  resetVisibilityConditionOfFieldSection: function(eInput) {
    var eHgrp = eInput.getParent().getParent('.hgrp');
    if (!eHgrp) return;
    var headerName = 
      eHgrp.get('class').replace(/.* hgrp_(\w+) .*/, '$1');
    if (headerName && this.visibilityConditions[headerName])
      (function() { this.visibilityConditions[headerName].fx.show(); }).delay(500, this);
  },
  
  initValues: {},
  
  initValidator: function() {
    this.validator = new Form.Validator.Inline(this.eForm, {
      ignoreHidden: false,
      evaluateFieldsOnBlur: false,
      onElementFail: function(eInput, name) {
        eInput.getParents('.element')[0].addClass('errorRow');
        this.resetVisibilityConditionOfFieldSection(eInput);
      }.bind(this),
      onElementPass: function(eInput, name) {
        eInput.getParents('.element')[0].removeClass('errorRow');
        this.resetVisibilityConditionOfFieldSection(eInput);
      }.bind(this)
    });
  },
  
  initMooRainbow: function() {
    this.eForm.getElements('.type_color').each(function(el){
      var eColor = el.getElement('div');
      var eInput = el.getElement('input');
      eInput.addEvent('change', function() {
        eColor.setStyle('background-color', eInput.value);
      });
      new MooRainbow(eInput, {
        id: 'rainbow_'+eInput.get('name'),
        imgPath: '/i/img/rainbow/small/',
        wheel: true,
        startColor: eInput.value ?
          new Color(eInput.value).rgb :
          [255, 255, 255],
        onChange: function(color) {
          eColor.setStyle('background-color', color.hex);
          eInput.value = color.hex;
        },
        onComplete: function(color) {
          eColor.setStyle('background-color', color.hex);
          eInput.value = color.hex;
        }
      });
    });
  },
  
  initAutoGrow: function() {
    this.eForm.getElements('textarea').each(function(el){
      new AutoGrow(el);
    });
  },
  
  fuOptions: null,
  
  initFancyUpload: function(options) {
    this.fuOptions = $merge({
      chooseBtnTitle: 'Выбрать',
      hideHelp: false
    }, options);
    this._initFancyUpload(this.eForm);
  },
  
  /**
   * options: {
   *   url: 'http://asdasd'
   *   loadedFiles: [ ... ]   // $_FILES format
   * }
   */
  _initFancyUpload: function(eContainer) {
    var name, eDiv, eBtn, eList, eInputComplete, opts, options;
    eContainer.getElements('input[type=file]').each(function(eInput) {
      if (this.fuOptions.hideHelp)
        eInput.getParent().getElement('.help').setStyle('display', 'none');
      // Заменяем стандартный input элементами интерфейса FancyUpload
      name = eInput.get('name');
      eDiv = new Element('div', {'class': 'fu-item'});
      eList = new Element('ul', {'class': 'fu-list'}).inject(eDiv);
      eBtn = Ngn.btn(this.fuOptions.chooseBtnTitle, 'btn2');
      eBtn.inject(eDiv, 'top');
      eDiv.inject(eInput, 'after');
      eInputComplete = (
        '<input type="hidden" class="'+
        (eInput.hasClass('required') ? 'validate-fancyUpload-required' : 'validate-fancyUpload')
        +'">').toDOM()[0].
        inject(eDiv);
      // Если файл уже загружен (режим редактирования)
      if (eInput.get('data-file')) {
        eInputComplete.set('value', 'complete');
      }
      var eFileNav = eInput.getParent('.element').getElement('.fileNav');
      options = $merge(this.fuOptions, {
        onOpening: function() {
          if (eFileNav) eFileNav.setStyle('display', 'none');
        },
        onFileStart: function() {
          eInputComplete.set('value', 'uploading');
        },
        onComplete: function(data) {
          eInputComplete.set('value', 'complete');
          this.validator.validateField(eInputComplete, true);
        }.bind(this)
      });
      eInput.dispose();
      opts = $merge({}, options);
      opts.url = opts.url.replace('{fn}', name); // Заменяем строку {fn} на имя поля
      opts.loadedFiles = [];
      if (options.loadedFiles && options.loadedFiles[name])
        opts.loadedFiles[0] = options.loadedFiles[name];
      new Ngn.UploadAttach(eList, eBtn, opts);
    }.bind(this));
  },
  
  initIframeRequest: function() {
    this.iframeRequest = new Ngn.IframeFormRequest.JSON(this.eForm);
    return this.iframeRequest;
  },
  
  addElements: function(eContainer) {
    if (this.isFancyUpload) this.form._initFancyUpload();
  },
  
  initFileNav: function() {
    this.eForm.getElements('.fileNav').each(function(eFileNav){
      Ngn.addAjaxAction(eFileNav.getElement('.sm-delete,.delete'), 'delete', function() {
        eFileNav.dispose();
      });
    });
  },
  
  submitAjax: function() {
    if (!this.validator.validate()) return;
    c(Ngn.frm.toObj(this.eForm));
    new Ngn.Request.JSON({
      url: this.eForm.get('action'),
      onComplete: function(r) {
        this.fireEvent('complete', r);
      }.bind(this)
    }).post(Ngn.frm.toObj(this.eForm));
  }
  
});

Ngn.Form.forms = {};
Ngn.Form.ElOptions = {};

Ngn.Form.ElInit = new Class({
  
  initialize: function(form, type) {
    this.form = form;
    this.type = type;
    this.init();
  },

  init: function() {
    this.form.eForm.getElements('.type_'+this.type).each(function(eRow) {
      var cls = eval('Ngn.Form.El.'+ucfirst(this.type));
      new cls(this.type, this.form, eRow);
    }.bind(this));
  }
  
});

Ngn.Form.ElInit.factory = function(form, type) {
  var cls = eval('Ngn.Form.ElInit.'+ucfirst(type));
  if (cls) return new cls(form, type);
  return new Ngn.Form.ElInit(form, type);
};

Ngn.Form.El = new Class({
  
  options: {},
  
  initialize: function(type, form, eRow) {
    this.type = type;
    this.form = form;
    this.eRow = eRow;
    this.name = eRow.getElement(Ngn.frm.selector).get('name');
    if (Ngn.Form.ElOptions[this.name]) this.options = Ngn.Form.ElOptions[this.name];
    this.init();
  },
  
  init: function() {}
  
});

Ngn.Form.El.Dd = new Class({
  Extends: Ngn.Form.El,
  
  initialize: function(type, form, eRow) {
    if (!Ngn.site.page.strName) throw new Error('StrName not defined');
    this.page = Ngn.site.page;
    this.parent(type, form, eRow);
  },
  
});

Ngn.Form.El.Wisiwig = new Class({
  Extends: Ngn.Form.El,
  
  init: function() {
    var eCol = this.eRow.getParent('.type_col');
    if (!eCol) return;
    Ngn.whenElPresents(this.eRow, '.mceLayout', function(eMceLayout) {
      var eColBody = eCol.getElement('.colBody');
      if (eColBody.getSize().x < eMceLayout.getSize().x)
        eColBody.setStyle('width', eMceLayout.getSize().x+'px');
      if (this.form.options.dialog) this.form.options.dialog.resizeByCols();
      // Если высота всех элементов колонки меньше
      var colH = eCol.getParent('.colSet').getSize().y;
      var els = eCol.getElements('.element');
      var allColElsH = 0;
      for (var i=0; i<els.length; i++) allColElsH += els[i].getSize().y;
      if (allColElsH < colH) {
        var eIframe = eMceLayout.getElement('iframe');
        eIframe.setStyle('height', (eIframe.getSize().y+colH-allColElsH)+'px');
      }
    }.bind(this));
  }
  
});

Ngn.TinySettings.Simple = new Class({
  Extends: Ngn.TinySettings,
  
  getSettings: function() {
    return $merge(this.parent(), {
      theme_advanced_buttons1: 'undo,redo,bold,italic,justifyleft,justifycenter,justifyright,formatselect,bullist,numlist,blockquote,cleanup,fullscreen',
      theme_advanced_buttons2: '',
      plugins: 'safari,fullscreen',
      valid_elements: 'a[href|target],i,em,strong,b,strikethrough,li,ul,ol,blockquote,h2,h3,h4,br,p'
    });
  }
  
});

Ngn.TinySettings.Simple.Links = new Class({
  Extends: Ngn.TinySettings.Simple,
  
  getSettings: function() {
    var s = this.parent();
    s.theme_advanced_buttons1 += ',link,unlink';
    return s;
  }
  
});


Ngn.Form.El.WisiwigSimple = new Class({
  Extends: Ngn.Form.El.Wisiwig,
  
  init: function() {
    var settings = new (this.getTinySettingsClass())().getSettings();
    if (this.options.tinySettings) settings = $merge(settings, this.options.tinySettings); 
    new Ngn.TinyInit({
      parent: this.form.eForm,
      selector: '.type_'+this.type+' textarea',
      settings: settings
    });
    this.parent();
  },
  
  getTinySettingsClass: function() {
    return Ngn.TinySettings.Simple;
  }

});

Ngn.Form.El.WisiwigSimple2 = new Class({
  Extends: Ngn.Form.El.WisiwigSimple,
  
  getTinySettingsClass: function() {
    return Ngn.TinySettings.Simple.Links;
  }
  
});

Ngn.Form.El.DdTagsTreeSelect = new Class({
  Extends: Ngn.Form.El,
  
  init: function() {
    this.eRow.getElements('a').each(function(el){
      var eUl = this.eRow.getElement('.nodes_'+el.get('data-id'));
      eUl.setStyle('display', 'none');
      /*
      eUl.store('fx', new Fx.Slide(eUl, {
        duration: 500,
        transition: Fx.Transitions.Pow.easeOut
      }));
      */
      //eUl.retrieve('fx').hide();
      el.addEvent('click', function(e) {
        //eUl.retrieve('fx').toggle();
        eUl.setStyle('display', eUl.getStyle('display') == 'block' ? 'none' : 'block');
        return false;
      });
    }.bind(this));
    this.eRow.getElements('input').each(function(el){
      if (el.get('checked')) this.openUp(el);
    }.bind(this));
  },
  
  openUp: function(el) {
    var eUl = el.getParent('ul');
    if (!eUl) return;
    //var fx = eUl.retrieve('fx');
    //if (!fx) return;
    eUl.setStyle('display', 'block');
    //fx.show();
    this.openUp(eUl);
  }
  
});

Ngn.Form.El.Textarea = new Class({
  Extends: Ngn.Form.El,
  
  init: function() {
    new Ngn.ResizableTextarea(this.eRow);
  }
  
});

Ngn.Form.El.DdTags = new Class({
  Extends: Ngn.Form.El.Dd,
  
  init: function() {
    var eInput = this.eRow.getElement('input');
    new TextboxList(eInput, {
      unique: true,
      plugins: {autocomplete: {
        queryRemote: true,
        remote: {
          url: '/c/ddTagsAc?strName='+this.page.strName+'&fieldName='+eInput.get('name')
        }
      }}
    });
  }
  
});
