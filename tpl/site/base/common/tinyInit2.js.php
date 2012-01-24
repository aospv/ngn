<?php
$tags = Config::getVar('tiny.'.$d.'.allowedTags');
$classes = Config::getVar('tiny.'.$d.'.classes', true);
if (is_array($classes)) $theme_advanced_styles = implode(';', $classes);
// Заменяем , на |
foreach ($tags as &$v) $v = str_replace(',', '|', $v); 
// Выбираем тэги без квадратных скобок с параметрами 
foreach ($tags as $v) $clearTags[] = preg_replace('/([^\[^\]]*)(\[.+\])*/', '$1', $v);

//if (!$cssFile = SFLM::getCachedUrl('s2/css/portal/tiny.css', true))
  //$cssFile = Config::getVar('mainCssFile');

?>

Ngn.TinyInitDd = new Class ({

  initialize: function(baseUrl, url, className) {
    this.url = url;
    this.baseUrl = baseUrl;
    
    this.elements = '';
    this.theme = 'simple';
    this.className = className;
    if (className) {
      var ids = new Array();
      document.getElements('textarea[class^='+className+']').each(function(element, n) {
        var id = element.getProperty('id');
        if (!id) {
          id = 'textarea'+n;
          element.setProperty('id', id);
        }
        ids[n] = id;
      });
      if (ids.length) this.elements = ids.join(',');
    }
  },
  
  initTiny: function() {
    var settings = {
      elements : this.elements,
      theme : this.theme
    };
    c('Tiny');
    if (this.className) {
      if (this.elements != '') {
        settings.mode = "exact";
        tinyMCE.init(settings);
      }
    } else {
      settings.mode = "textareas";
      tinyMCE.init(settings);
    }
    
  }

});
