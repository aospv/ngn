if (!Ngn) var Ngn = {};

MooTools.lang.setLanguage('ru-RU');

// ----------------------------------------------------------

Element.implement({
  values: function(){
    var r = {};
    this.getElements('input').each(function(el){
      if (el.get('type') == 'radio') {
        if (el.get('checked')) {
          r = el.get('value');
        }
      } else
        if (el.get('type') == 'checkbox') {
        if (el.get('checked')) {
          r[el.get('name')] = el.get('value');
        }
      } else {
        r[el.get('name')] = el.get('value');
      }
    });
    return r;
  },
  getSizeWithMargin: function() {
    var s = this.getSize();
    return {
      x: parseInt(this.getStyle('margin-left')) + parseInt(this.getStyle('margin-right')) + s.x,
      y: parseInt(this.getStyle('margin-top')) + parseInt(this.getStyle('margin-bottom')) + s.y
    };
  },
  getSizeWithoutBorders: function() {
    var s = this.getSize();
    return {
      x: s.x -
        parseInt(this.getStyle('border-left-width')) -
        parseInt(this.getStyle('border-right-width')),
      y: s.y -
        parseInt(this.getStyle('border-top-width')) -
        parseInt(this.getStyle('border-bottom-width'))
    };
  },
  getSizeWithoutPadding: function() {
    var s = this.getSize();
    return {
      x: s.x -
        parseInt(this.getStyle('padding-left')) -
        parseInt(this.getStyle('padding-right')),
      y: s.y -
        parseInt(this.getStyle('padding-top')) -
        parseInt(this.getStyle('padding-bottom'))
    };
  },
  setSize: function(s) {
    if (!s.x && !s.y) throw new Error('No sizes defined');
    if (s.x) this.setStyle('width', s.x + 'px');
    if (s.y) this.setStyle('height', s.y + 'px');
    this.fireEvent('resize');
  }
});

Element.implement(Events);

String.implement({
  
  toDOM: function(){
    var wrapper = this.test('^<the|^<tf|^<tb|^<colg|^<ca') && ['<table>', '</table>', 1] ||
            this.test('^<col') && ['<table><colgroup>', '</colgroup><tbody></tbody></table>',2] ||
            this.test('^<tr') && ['<table><tbody>', '</tbody></table>', 2] ||
            this.test('^<th|^<td') && ['<table><tbody><tr>', '</tr></tbody></table>', 3] ||
            this.test('^<li') && ['<ul>', '</ul>', 1] ||
            this.test('^<dt|^<dd') && ['<dl>', '</dl>', 1] ||
            this.test('^<le') && ['<fieldset>', '</fieldset>', 1] ||
            this.test('^<opt') && ['<select multiple="multiple">', '</select>', 1] ||
            ['', '', 0];
    var el = new Element('div', {html: wrapper[0] + this + wrapper[1]}).getChildren();
    while(wrapper[2]--) el = el[0].getChildren();
    return el;
  }

});

Ngn.bindSizes = function(eFrom, eTo) {
  eFrom.addEvent('resize', function() {
    eTo.setSize(eFrom.getSize());
  });
};

Hash.implement({
  length: function() {
    var l = 0;
    this.each(function(){ l++ });
    return l;
  }
});

Array.prototype.max = function() {
  var max = this[0];
  var len = this.length;
  for (var i = 1; i < len; i++) if (this[i] > max) max = this[i];
  return max;
}
/*
Array.prototype.min = function() {
  var min = this[0];
  var len = this.length;
  for (var i = 1; i < len; i++) if (this[i] < min) min = this[i];
  return min;
}
*/

//--------------------------------------------------------------------------

Ngn.checkboxesSelected = function(esCheckboxes) {
  var selected = false;
  esCheckboxes.each(function(el){
    if (selected) return;
    if (el.get('checked')) selected = true;
  });
  return selected;
}; 

// --------------------------Common functions------------------------------

function c(t) {
  //throw new Error('!');
  if ($defined(console) && console.log) console.log(t);
};

/*
function swtch(id) { 
  var o = document.getElementById(id);
  if (o) {
    if (o.style.display == "none" || o.style.display == "") {
      o.style.display = "block";
      //saveCookie(id, o.style.display);
      return true;
    } else {
      o.style.display = "none";
      //saveCookie(id, o.style.display);
      return false;
    }
  }
};

function openwin(url, w, h) {
  w+=40;
  h+=30;
  window.open(url,'','width='+w+', height='+h+', status=true, toolbar=false, resizable=true, scrollbars=false');
  return false;
};

*/

var name2id = function(name) {
  return name.replace(/\[/g, '_').replace(/\]/g, '').replace(/-/g, '_');
};

var mapRu = 'Ё|©|Й|Ц|У|К|Е|Н|Г|Ш|Щ|З|Х|Ъ|Ф|Ы|В|А|П|Р|О|Л|Д|Ж|Э|Я|Ч|С|М|И|Т|Ь|Б|Ю|ё|й|ц|у|к|е|н|г|ш|щ|з|х|ъ|ф|ы|в|а|п|р|о|л|д|ж|э|я|ч|с|м|и|т|ь|б|ю| |'.split('|');
var mapEn = 'E|N|Y|TS|U|K|E|N|G|SH|SCH|Z|H|-|F|I|V|A|P|R|O|L|D|ZH|E|JA|CH|S|M|I|T|-|B|JU|e|y|tc|u|k|e|n|g|sh|sch|z|h|-|f|i|v|a|p|r|o|l|d|zh|e|ja|ch|s|m|i|t|-|b|ju|-|'.split('|');

function translate(str){
  for(i = 0; i < mapRu.length; ++i) {
    j = 0;
    if (!mapRu[i]) continue;
    while (str.indexOf(mapRu[i]) >= 0){
      str = str.replace(mapRu[i], mapEn[i]);
      j++;
      if (j>10) {
        break;
      }
    }
  }
  str = str.replace(/(\W)/g,'-').toLowerCase();
  return str;
};

function trim(s) {
  return s.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
};

function numerical(s) {
  if (s == 0) return true;
  var _s = s.toInt().toString();
  return _s.length == s.length && _s != 0;
};

function abbreviate(elements, n) {
  elements.each(function(el){
    var t = el.get('text');
    if (t.length > n) {
      el.set('text', t.substr(0, n) + '...');
      el.set('title', t);
      el.addClass('tooltip');
    }
  });
};

function ucfirst(str) {
  var f = str.charAt(0).toUpperCase();
  return f + str.substr(1, str.length-1);
};

Ngn.regNamespace = function(namespace, lastArray) {
  var parts = namespace.split('.');
  var brackets = '{}';
  for (var i=0; i<parts.length; i++) {
    if (lastArray && i == parts.length-1) brackets = '[]';
    var str = parts.slice(0, i+1).join('.');
    //c('if (!'+str+') { c(str); window.'+str+' = '+brackets+'; c("reg "+str); }');
    //eval('if (!'+str+') { c(str); window.'+str+' = '+brackets+'; c("reg "+str); }');
    eval('if (!'+str+') window.'+str+' = '+brackets);
  }
};

Ngn.getPath = function(n) {
  if (n === 0) return './'; 
  var p = window.location.pathname.split('/');
  var s = '';
  if (!n) n = p.length-1;
  for (var i=1; i<=n; i++) {
    s += '/' + (p[i] ? p[i] : 0);
    if (n === i) break;
  }
  return s;
};

Ngn.getParam = function(n, zeroOnUndefined) {
  return Ngn._getParam(window.location.pathname, n+1, zeroOnUndefined);
};

Ngn._getParam = function(url, n, zeroOnUndefined) {
  var p = url.split('/');
  return $defined(p[n]) ? p[n] : ($defined(zeroOnUndefined) ? 0 : false);
};

Ngn.getRandomInt = function(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
};

Ngn.randString = function(len) {
  var allchars = 'abcdefghijknmpqrstuvwxyzABCDEFGHIJKLNMPQRSTUVWXYZ'.split('');
  var string = '';
  for (var i = 0; i < len; i++) {
    string += allchars[Ngn.getRandomInt(0, allchars.length-1)];
  }
  return string;
};

Ngn.btn = function(title, btnClass, properties) {
  var p = new Hash({
    'href': '#',
    'class': 'btn'+(btnClass ? ' '+btnClass : '')
  }).combine(properties);
  var btn = new Element('a', p.getClean());
  new Element('span', {
    'html': title
  }).inject(btn);
  return btn;
};

Ngn.smBtns = function(btns, bordered) {
  if (!bordered) bordered = true;
  var html = '<div class="smIcons'+(bordered ? ' bordered' : '')+'">';
  for (var i=0; i<btns.length; i++) {
    opts = $merge({
      href: '#',
      bordered: true
    }, btns[i].opts ? btns[i].opts : {});
    html += '<a href="'+opts.href+'" class="sm-'+btns[i].name+'" title="'+btns[i].title+'"><i></i></a>';
  }
  html += '</div>';
  return html.toDOM()[0];
};

Ngn.setToCenter = function(element, eParent, offset) {
  if (!$defined(eParent)) eParent = element.getParent();
  if (!offset) offset = {};
  offset = Object.merge({x: 0, y: 0}, offset);
  element.setStyles({
    'top': Math.round(eParent.getSize().y/2 - element.getSize().y/2 + offset.y),
    'left': Math.round(eParent.getSize().x/2 - element.getSize().x/2 + offset.x)
  });
};

Ngn.setToCenterHor = function(element, eParent) {
  if (!$defined(eParent)) eParent = element.getParent();
  element.setStyles({
    'left': Math.round(eParent.getSize().x/2 - element.getSize().x/2)
  });
};

Ngn.setToCenterRelVer = function(element, eParent) {
  if (!$defined(eParent)) eParent = element.getParent();
  element.setStyles({
    'margin-top': Math.round(eParent.getSize().y/2 - element.getSize().y/2) + 'px'
  });
};

Ngn.setToCenterRelHor = function(element, eParent) {
  if (!$defined(eParent)) eParent = element.getParent();
  element.setStyles({
    'margin-left': Math.round(eParent.getSize().x/2 - element.getSize().x/2) + 'px'
  });
};

Ngn.setToTopRight = function(element, eParent, margin) {
  if (!$defined(eParent)) eParent = element.getParent();
  if (!$defined(margin)) margin = [0, 0];
  element.setStyles({
    'top': margin[1],
    'left': eParent.getSize().x - element.getSize().x - margin[0]
  });
};

Ngn.setToBottomRight = function(element, eParent, margin) {
  if (!$defined(eParent)) eParent = element.getParent();
  if (!$defined(margin)) margin = [0, 0];
  element.setStyles({
    'top': eParent.getSize().y - margin[1],
    'left': eParent.getSize().x - element.getSize().x - margin[0]
  });
};

Ngn.setToCenterRight = function(element, eParent, margin) {
  if (!$defined(eParent)) eParent = element.getParent();
  if (!$defined(margin)) margin = [0, 0];
  element.setStyles({
    'top': Math.round(eParent.getSize().y/2 - element.getSize().y/2) - margin[1],
    'left': eParent.getSize().x - element.getSize().x - margin[0]
  });
};

Ngn.setToCenterLeft = function(element, eParent, margin) {
  if (!$defined(eParent)) eParent = element.getParent();
  if (!$defined(margin)) margin = [0, 0];
  element.setStyles({
    'top': Math.round(eParent.getSize().y/2 - element.getSize().y/2) - margin[1],
    'left': margin[0]
  });
};

Ngn.setToCenterBlock = function(element, eWidth) {
  element.setStyles({
    'margin-left': Math.round(eWidth.getSize().x/2 - element.getSize().x/2)
  });
};

MooTools.lang.set('ru-RU', 'FancyUpload', {
  'fileName': '{name}',
  'cancel': 'Отмена',
  'cancelTitle': 'Кликните, что бы отменить загрузку и удалить запись',
  'validationErrors': {
    'duplicate': 'Файл <em>{name}</em> ужа добавлен, дубликаты не допускаются.',
    'sizeLimitMin': 'Файл <em>{name}</em> (<em>{size}</em>) слишком маленький, минимальный размер <em>{fileSizeMin}</em>.',
    'sizeLimitMax': 'Файл <em>{name}</em> (<em>{size}</em>) слишком большой, максимальный размер <em>{fileSizeMax}</em>.',
    'fileListMax': 'Файл <em>{name}</em> не может быть добавлен, максимальное количество файлов <em>{fileListMax}.',
    'fileListSizeMax': 'Файл <em>{name}</em> (<em>{size}</em>) слишком большой, максимальный суммарный размер всех файлов <em>{fileListSizeMax}</em>.'
  },
  'errors': {
    'httpStatus': 'Сервер вернул HTTP-код #{code}',
	'securityError': 'Ошибка безопасности ({text})',
	'ioError': 'Произошла ошибка загрузки или сохранения ({text})'
  },
  'linuxWarning': 'Warning: Due to a misbehaviour of Adobe Flash Player on Linux,\nthe browser will probably freeze during the upload process.\nDo you want to start the upload anyway?'
});

Ngn.tpl = function(tpl, data) {
  return tpl.replace(/\{(\w+)\}/g, function(str, name) {
    return data[name] ? data[name] : '';
  });
};

Ngn.RequiredOptions = new Class({
  Extends: Options,
  
  requiredOptions: [],
  
  setOptions: function(options) {
    this.parent(options);
    for (var i; i++; i<this.requiredOptions.length) {
      if (!this.options[this.requiredOptions[i]])
        throw new Error('Required option ' + this.requiredOptions[i] + ' not defined');
    }
    return this;
  }

});

Ngn.initSubmit = function(eForm) {
  var btnSubmit = eForm.getElement('input[type=submit]');
  if (!btnSubmit) return;
  var submiting = false;
  btnSubmit.addEvent('click', function(e){
    new Event(e).stop();
    if (submiting) return;
    btnSubmit.disabled = true;
    btnSubmit.addClass('loading');
    if (this.validator.validate()) {
      submiting = true;
      eForm.submit();
    }
  });
};

Ngn.clearParagraphs = function(s) {
  return s.replace(/(<p>)(&nbsp;)?(<\/p>)/g, '').replace(/\n/g, '');
}

Ngn.localStorage = {
  clean: function() {
    if (!localStorage) return;
    try {
      for (k in localStorage) {
        localStorage.removeItem(k);
      }
    } catch (e) {
      for (var i=0; i < localStorage.length; i++)
        localStorage.removeItem(localStorage[i]);
    }
  },
  remove: function(key) {
    if (!localStorage) return false;
    localStorage.removeItem(key);
  }
};
Ngn.localStorage.json = {
  get: function(key) {
    if (!localStorage) return false;
    return JSON.decode(localStorage.getItem(key));
  },
  set: function(key, data) {
    localStorage.setItem(key, JSON.encode(data));
  }
};

Ngn.storage = {
  get: function(key) {
    if (localStorage) {
      var v = localStorage.getItem(key);
    } else {
      var v = Cookie.read(key);
    }
    if (v == 'false') return false;
    else if (v == 'true') return true;
    else return v;
  },
  set: function(key, value) {
    if (localStorage)
      localStorage.setItem(key, value);
    else
      Cookie.write(key, value);
  }
  //bset: function(key, value) {
  //  this.set(key, value ? 1 : 0);
  //},
  //bget: function(key, value) {
  //  this.get(key);
  //}
};

Ngn.storage.int = {
  
  get: function(key) {
    return parseInt(Ngn.storage.get(key));
  }
  
};

Ngn.storage.json = {
  get: function(key) {
    if (localStorage) {
      return Ngn.localStorage.json.get(key);
    } else {
      return JSON.decode(Cookie.read(key));
    }
  },
  set: function(key, data) {
    if (localStorage)
      Ngn.localStorage.json.set(key, data);
    else
      Cookie.write(key, JSON.encode(data));
  }
};

Ngn.addHover = function (el, hoverClass) {
  el.addEvent('mouseover', function() {
    this.addClass(hoverClass);
  });
  el.addEvent('mouseout', function() {
    this.removeClass(hoverClass);
  });
};

Ngn.loading = function(state) {
  var el = $('globalLoader');
  if (!el) {
    var el = '<div id="globalLoader" class="globalLoader"></div>'.toDOM()[0].inject(document.getElement('body'), 'top');
    el.setStyle('top', window.getScroll().y);
    window.addEvent('scroll', function() {
      el.setStyle('top', window.getScroll().y);
    });
  }
  el.setStyle('visibility', state ? 'visible' : 'hidden');
};

Ngn.opacityBtn = function(eBtn, outOp, overOp) {
  var fx = new Fx.Morph(eBtn, { duration: 'short', link: 'cancel' });
  if (!$defined(outOp)) outOp = 0.4;
  if (!$defined(overOp)) overOp = 1;
  eBtn.set('opacity', outOp);
  eBtn.addEvent('mouseover', function() { fx.start({'opacity': [outOp, overOp]}); });
  eBtn.addEvent('mouseout', function() { fx.start({'opacity': [overOp, outOp]}); });
  return eBtn;
};

Ngn.hHandler = function(eHandler, eContainer, wId) {
  var w = Ngn.storage.get(wId);
  if (w) eContainer.setStyle('width', w);
  new Drag(eContainer, {
    handle: eHandler,
    modifiers: {x: 'width', y: false},
    snap: 0,
    onComplete: function(el) {
      Ngn.storage.set(wId, el.getStyle('width'));
    }
  });
};

Ngn.addWrapper = function(el, wrapperClass) {
  var wrapper = new Element('div', {'class':wrapperClass}).inject(el, 'before');
  el.inject(wrapper);
  return wrapper;
};

function number_format( number, decimals, dec_point, thousands_sep ) {  // Format a number with grouped thousands
  // 
  // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfix by: Michael White (http://crestidg.com)

  var i, j, kw, kd, km;

  // input sanitation & defaults
  if( isNaN(decimals = Math.abs(decimals)) ){
    decimals = 2;
  }
  if( dec_point == undefined ){
    dec_point = ",";
  }
  if( thousands_sep == undefined ){
    thousands_sep = ".";
  }

  i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

  if( (j = i.length) > 3 ){
    j = j % 3;
  } else{
    j = 0;
  }

  km = (j ? i.substr(0, j) + thousands_sep : "");
  kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
  kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");

  return km + kw + kd;
}


Ngn.filesizeFormat = function(filesize) {
  if (filesize >= 1073741824) {
    filesize = number_format(filesize / 1073741824, 2, '.', '') + ' Gb';
  } else { 
    if (filesize >= 1048576) {
      filesize = number_format(filesize / 1048576, 2, '.', '') + ' Mb';
    } else { 
      if (filesize >= 1024) {
        filesize = number_format(filesize / 1024, 0) + ' Kb';
      } else {
        filesize = number_format(filesize, 0) + ' bytes';
      };
    };
  };
  return filesize;
};

Ngn.getElementJson = function(element) {
  return JSON.decode(element.get('html'));
};

Ngn.addUrlParam = function(url, k, v) {
  return url+(url.contains('?') ? '&' : '?')+k+'='+v;
};

Ngn.fixEmptyTds = function(el) {
  var tds = el.getElements('td');
  for (var i=0; i<tds.length; i++)
    if (!trim(tds[i].get('html'))) tds[i].set('html', '&nbsp;');
};

Ngn.addBtnAction = function(selector, action, parent) {
  eBtn = (parent ? parent : document).getElement(selector);
  if (!eBtn) return;
  action = action.pass(eBtn);
  eBtn.addEvent('click', function(e) {
    new Event(e).stop();
    action();
  });
};

Ngn.addBtnsAction = function(selector, action, parent) {
  (parent ? parent : document).getElements(selector).each(function(eBtn) {
    eBtn.addEvent('click', function(e) {
      new Event(e).stop();
      action(eBtn);
    });
  });
};

Ngn.confirm = function(question) {
  if (!question) question = 'Вы уверены?';
  return confirm(question);
};

Ngn.addAjaxAction = function(eBtn, action, onComplete) {
  if (!eBtn) return;
  onComplete = onComplete ? onComplete : $empty;
  eBtn.addEvent('click', function(e) {
    e.preventDefault();
    if (eBtn.hasClass('confirm') && !Ngn.confirm()) return;
    if (eBtn.hasClass('loading')) return;
    if (eBtn.retrieve('disabled')) return;
    eBtn.addClass('loading');
    new Ngn.Request({
      url: eBtn.get('href').replace(action, 'ajax_'+action),
      onComplete: function() {
        onComplete();
        eBtn.removeClass('loading');
      }
    }).send();
  });
};

Ngn.addBtnInit = function(selector, init) {
  eBtn = document.getElement(selector);
  if (!eBtn) return;
  init.pass(eBtn)();
}

Ngn.tpls = {};

Ngn.strReplace = function(search, replace, subject) {
  if (!(replace instanceof Array)) {
    replace = new Array(replace);
    if (search instanceof Array) {
      while (search.length>replace.length) {
        replace[replace.length] = replace[0];
      }
    }
  }
  if (!(search instanceof Array)) search = new Array(search);
  while (search.length>replace.length) {
    replace[replace.length]='';
  }
  if(subject instanceof Array){
    for(k in subject){
      subject[k] = Ngn.strReplace(search, replace, subject[k]);
    }
    return subject;
  }
  for (var k=0; k<search.length; k++) {
    var i = subject.indexOf(search[k]);
    while(i>-1) {
      subject = subject.replace(search[k], replace[k]);
      i = subject.indexOf(search[k],i);
    }
  }
  return subject;
};

Ngn.equalItemHeights = function(esItems) {
  if (!esItems.length) return;
  var maxY = 0;
  var vPadding =
    esItems[0].getStyle('padding-top').toInt() +
    esItems[0].getStyle('padding-bottom').toInt() +
    esItems[0].getStyle('border-top-width').toInt() +
    esItems[0].getStyle('border-bottom-width').toInt();
  esItems.each(function(el) {
    var y = el.getSize().y;
    if (y > maxY) {
      maxY = y;
    }
  });
  if (!maxY) return;
  maxY = maxY - vPadding;
  esItems.each(function(el){
    el.setStyle('height', maxY);
  });
},

Ngn.cutElementText = function(el, length) {
  if (el.get('text').length <= length) return;
  var text = el.get('text');
  el.set('text', text.substr(0, length) + '...');
  el.set('title', text);
};

Ngn.whenElPresents = function(eParent, selector, func) {
  var el;
  find = function() {
    el = document.getElement(selector);
    if (el) return true;
    return false;
  };
  if (find()) {
    func(el);
    return;
  }
  var maxAttempts = 10;
  var n = 1;
  var id = function() {
    n++;
    if (find()) {
      $clear(id);
      func(el);
      return;
    }
    if (n == maxAttempts) $clear(id);
  }.periodical(100);
};
