<?php

class HtmlParser {

  static public function getElementContent($html, $pseudoCssSelector) {
     foreach (explode('>', $pseudoCssSelector) as $selector) {
       $selector = trim($selector);
       self::getElementContent2($html, $selector);
     }
  }
  
  static private function getElementContent2($html, $selector) {
    preg_match('/([a-z0-9]+)\.([a-z0-9]+)/', $selector, $m);
    preg_match_all('/<'.$m[1].'[^>]*class="[^"]*'.$m[2].'[^"]*"[^>]*>(.*)<\/\s*'.$m[1].'\s*>/U', $html, $m);
  }
  
  static public function replaceElements($html, $selector, $replacer) {
    $replacer = '<div>123</div>{element}';
    foreach (explode('>', $selector) as $sel) {
      //$sel
    }
    preg_match('/([a-z0-9]+)\.([a-z0-9]+)/', $selector, $m);
  }
  
}
