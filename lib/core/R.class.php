<?php

class R {

  /*
   * @the vars array
   * @access private
   */
  static public $vars = array();
  
  static function get($k) {
    return isset(self::$vars[$k]) ? self::$vars[$k] : false;
  }
  
  static function set($k, $v) {
    self::$vars[$k] = $v;
    return $v;
  }
  
  static function increment($k) {
    if (!isset(self::$vars[$k])) {
      self::$vars[$k] = 1;
      return;
    }
    self::$vars[$k]++;
  }
  
}
