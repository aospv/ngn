<?php

class Ngn {

  static protected $events = array();

  static public function addEvent($name, Closure $func) {
    self::$events[$name] = $func;
  }
  
  static public function fireEvent($name, $params = null) {
    $params = (array)$params;
    if (($func = Config::getVar('event.'.$name, true)) !== false and is_callable($func))
      return call_user_func_array($func, $params);
    elseif (isset(self::$events[$name]))
      return call_user_func_array(self::$events[$name], $params);
  }
  
  static public function __callstatic($a, $b) {
    die2(array($a, $b));
  }
  
  /*
  static $t;
  
  static public function t($in) {
    if (isset(self::$t[$in])) return self::$t[$in];
    return $in;
  }
  */

}