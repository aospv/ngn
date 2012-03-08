<?php

class CssCore {

  static public function extendSelector($css, $baseSelector, $selector) {
    preg_replace('/'.str_replace('.', '\\.', $baseSelector).'.*{');
    return str_replace($baseSelector, $baseSelector.', '.$selector);
  }
  
  static public function wrapSelectors($css, $wrapSelector) {
                    // ревнивый *+
    return preg_replace_callback('/(\s*+)(.+)(\s*+\{[^\}]*\})/Ums', function($m) use ($wrapSelector) {
      return $m[1].$wrapSelector.' '.$m[2].$m[3];
    }, $css);
  }
  
  static public function getProloadJs($css) {
    if (!preg_match_all('/url\(([^\)]+)\)/', $css, $m)) return '';
    $js = "";
    foreach ($m[1] as $url)
      $js .= "new Image().src = '$url';\n";
    return "\n(function() {\n".$js."}).delay(1000);\n";
  }

}
