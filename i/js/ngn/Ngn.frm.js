Ngn.frm = {};

Ngn.frm.html = {};

Ngn.frm.selector = 'input,select,textarea';

Ngn.frm.virtualElements = [];

Ngn.frm.virtualElement = {
  
  setDisabled: function(flag) {},
  
  initVirtualElement: function() {
    Ngn.frm.virtualElements.push(this);
  }
  
};

Ngn.frm.getValueByName = function(name) {
  return Ngn.frm.getValue(Ngn.frm.getElements(name));
};

Ngn.frm.getValue = function(elements) {
  var r = null;
  elements.each(function(el){
    var type = el.get('type');
    if (type == 'radio') {
      if (el.get('checked'))
        r = el.get('value');
    } else {
      r = el.get('value');
    }
  });
  return r;
};

Ngn.frm.getElements = function(name) {
  var elements = [];
  var n = 0;
  $$(Ngn.frm.selector).each(function(el) {
    if (el.get('name') != name) return;
    elements[n] = el;
    n++;
  });
  return elements;
};

Ngn.frm.disable = function(eForm, flag) {
  eForm.getElements(Ngn.frm.selector).each(function(el){
    el.set('disabled', flag);
  });
  Ngn.frm.virtualElements.each(function(o){
    o.setDisabled(flag);
  });
};

// формат classback ф-ии должен быть следующим:
// function (fieldValue, args) {}
Ngn.frm.addEvent = function(event, name, callback, args) {
  var elements = Ngn.frm.getElements(name);
  elements.each(function(el){
    el.addEvent(event, function(e){
      callback.run([Ngn.frm.getValue(elements), args]);
    });
  });
}

Ngn.frm.VisibilityCondition = new Class({
  
  initialize: function(eForm, headerName, condFieldName, cond) {
    this.eSection = eForm.getElement('.hgrp_' + headerName);
    if (!this.eSection) {
      alert('Element ".hgrp_' + headerName + '" does not exists');
      return;
    }
    this.fx = new Fx.Slide(this.eSection, {
      duration: 200,
      transition: Fx.Transitions.Pow.easeOut
    });
    this.fx.hide();
    var toggleSection = function(v, isFx){
      // v необходима для использования в условии $d['cond']
      var flag = (eval(cond));
      if (!flag) {
        // Если скрываем секцию, необходимо снять все required css-классы в её полях
        this.eSection.getElements('.required').each(function(el) {
          el.removeClass('required');
          el.addClass('required-disabled');
        });
      } else {
        this.eSection.getElements('.required-disabled').each(function(el) {
          el.removeClass('required-disabled');
          el.addClass('required');
        });
      }
      if (isFx) {
        // если нужно завернуть не развёрнутую до конца секцию,
        // нужно просто скрыть её
        if (flag == this.fx.open)
          flag ?
            (function () { this.fx.show(); }).delay(200, this) :
            (function () { this.fx.hide(); }).delay(200, this);
        else
          flag ? this.fx.slideIn() : this.fx.slideOut();
      } else {
        return flag ? this.fx.show() : this.fx.hide();
      }
    }.bind(this);
    toggleSection(Ngn.frm.getValueByName(condFieldName), false);
    Ngn.frm.addEvent('change', condFieldName, toggleSection, true);
    Ngn.frm.addEvent('focus', condFieldName, toggleSection, true);
  }
  
});

Ngn.frm.headerToggleFx = function(btns) {
  btns.each(function(btn) {
    var eToggle = btn.getParent().getParent();
    btn.getParent().inject(eToggle, 'before');
    var setArrow = function(opened) {
      btn.set('value', '  '+(opened ? '↑' : '↓')+'  ');
    };
    var fx = new Fx.Slide(eToggle, {
      duration: 300,
      transition: Fx.Transitions.Pow.easeOut,
      onComplete: function() {
        setArrow(opened);
        Ngn.storage.set(btn.get('data-name'), opened ? 1 : 0);
      }
    });
    var opened = true;
    var saved = Ngn.storage.get(btn.get('data-name'));
    if (!saved || saved == 0) {
      fx.hide();
      opened = false;
    }
    if (saved != undefined) setArrow(opened);
    btn.addEvent('click', function(e) {
      new Event(e).stop();
      opened ? fx.slideOut() : fx.slideIn();
      opened = !opened;
    });
  });
};

Ngn.frm.headerToggle = function(esBtns) {
  esBtns.each(function(el){
    new Ngn.frm.HeaderToggle(el);
  });
};

Ngn.frm.HeaderToggle = new Class({
  Implements: [Options, Events],
  
  opened: false,
  
  initialize: function(eBtn, options) {
    this.setOptions(options);
    this.eBtn = eBtn;
    this.eHeader = this.eBtn.getParent();
    this.eToggle = this.eBtn.getParent().getParent();
    this.eHeader.inject(this.eToggle, 'before');
    var saved = Ngn.storage.get(eBtn.get('data-name'));
    if (saved == undefined) this.toggle(this.opened); 
    else this.toggle(saved);
    this.eBtn.addEvent('click', function(e) {
      new Event(e).stop();
      this.toggle(!this.opened);
      Ngn.storage.set(this.eBtn.get('data-name'), this.opened);
    }.bind(this));
  },
  
  toggle: function(opened) {
    opened ?
      this.eHeader.removeClass('headerToggleClosed') :
      this.eHeader.addClass('headerToggleClosed');
    this.eBtn.set('value', '  '+(opened ? '↑' : '↓')+'  ');
    this.eToggle.setStyle('display', opened ? 'block' : 'none');
    this.opened = opened;
    this.fireEvent('toggle', opened);
  }
  
});


Ngn.enumm = function(arr, tpl, glue) {
  if (!$defined(glue)) glue = '';
  for (var i=0; i<arr.length; i++)
    arr[i] = tpl.replace('{v}', arr[i]);
  return arr.join(glue);
};


Ngn.frm.getBracketNameKeys = function(name) {
  var m;
  m = name.match(/([^[]*)\[/);
  if (!m) return [name];
  var keys = [];
  keys.extend([m[1]]);
  var re = /\[([^\]]*)\]/g;
  while (m = re.exec(name)) {
    keys.extend([m[1]]);
  }
  return keys;
};

Ngn.frm.fillEmptyObject = function(object, keys) {
  for (var i=0; i<keys.length-1; i++) {
    var p = 'object'+(Ngn.enumm(keys.slice(0, i+1), "['{v}']"));
    eval('if (!$defined('+p+')) '+p+' = {}');
  }
};

Ngn.frm.setValueByBracketName = function(o, name, value) {
  var _name = name.replace('[]', '');
  if (!(o instanceof Object)) throw new Error('o is not object');
  var keys = Ngn.frm.getBracketNameKeys(_name);
  Ngn.frm.fillEmptyObject(o, keys);
  var p = 'o';
  for (var i=0; i<keys.length; i++) p += "['"+keys[i]+"']";
  if (name.contains('[]')) {
    eval(p+' = $defined('+p+') ? '+p+'.concat(value) : [value]');
  } else {
    //eval(p+' = $defined('+p+') ? [].concat('+p+', value) : value');
    eval(p+' = value');
  }
  return o;
};

Ngn.frm.objTo = function(eContainer, obj) {
  for (var i in obj) {
    eContainer.getElement('input[name='+i+']').set('value', obj[i]);
  }
};

Ngn.frm.toObj = function(eContainer, except) {
  var rv = {};
  var name;
  except = except || [];
  eContainer = $(eContainer);
  var typeMatch =
    'text' + 
    (!except.contains('hidden') ? '|hidden' : '') + 
    (!except.contains('password') ? '|password' : '');
  var elements = eContainer.getElements(Ngn.frm.selector);
  for (var i = 0; i < elements.length; i++) {
    var el = elements[i];
    if (!el.name) continue;
    var pushValue = undefined;
    if (el.get('tag') == 'textarea' && el.get('aria-hidden')) {
      // Значит из этой texarea был сделан tinyMce
      pushValue = tinyMCE.get(el.get('id')).getContent();
    } else if (
      (el.get('tag') == 'input' && 
         el.type.match(new RegExp('^'+typeMatch+'$', 'i'))) ||
       el.get('tag') == 'textarea' ||
      (el.get('type').match(/^checkbox|radio$/i) && el.get('checked'))
    ) {
      pushValue = el.value;
    } else if (el.get('tag') == 'select') {
      if (el.multiple) {
        var pushValue = [];
        for (var j = 0; j < el.options.length; j++)
          if (el.options[j].selected)
            pushValue.push(el.options[j].value);
        if (pushValue.length == 0) pushValue = undefined;
      } else {
        pushValue = el.options[el.selectedIndex].value;
      }
    }
    if (pushValue != undefined) {
      Ngn.frm.setValueByBracketName(rv, el.name, pushValue);
    }
  }
  return rv;
};

Ngn.frm.initTranslateField = function(eMasterField, eTranslatedField) {
  var eMasterField = $(eMasterField);
  var eTranslatedField = $(eTranslatedField);
  var translatedValueExists = eTranslatedField.get('value') ? true : false;
  var translatedFieldEdited = false;
  var translateField = function() {
    if (translatedValueExists || translatedFieldEdited) return;
    eTranslatedField.set('value', translate(trim(eMasterField.get('value'))));
  };
  eMasterField.addEvent('keyup', translateField);
  eMasterField.addEvent('blur', translateField);
  eMasterField.addEvent('click', translateField);
  eTranslatedField.addEvent('keyup', function(e){
    translatedFieldEdited = true;
  });
};

Ngn.frm.initCopySelectValue = function(eSelectField, eSlaveField, param) {
  if (!$defined(param)) param = 'value';
  var eSelectField = $(eSelectField);
  var eSlaveField = $(eSlaveField);
  eSlaveField.addEvent('keyup', function(){
    eSlaveField.store('edited', true);
  });
  eSelectField.addEvent('change', function(){
    if (eSlaveField.retrieve('edited')) return;
    eSlaveField.set('value', eSelectField.options[eSelectField.selectedIndex].get(param));
    eSlaveField.fireEvent('blur');
  });
};

Ngn.frm.initCopySelectTitle = function(eSelectField, eSlaveField) {
  Ngn.frm.initCopySelectValue(eSelectField, eSlaveField, 'text');
};

Ngn.frm.makeDialogabble = function(eLink, action, options) {
  eLink.addEvent('click', function(e) {
    new Event(e).stop();
    new Ngn.Dialog.RequestForm(Object.merge({
      url: eLink.get('href').replace(action, 'json_'+action),
      onSubmitSuccess: function() {
        window.location.reload();
      }
    }, options || {}));
 });
};

Ngn.frm.storable = function(eInput) {
  if (!eInput.get('id')) throw new Error('ID param mast be defined');
  var store = function() {
    Ngn.storage.set(eInput.get('id'), eInput.get('value'));
  };
  var restore = function() {
    eInput.set('value', Ngn.storage.get(eInput.get('id')));
  };
  restore();
  eInput.addEvent('keypress', function() {
    (function() {
      store();
    }).delay(100);
  });
  eInput.addEvent('blur', function() {
    store();
  });
}