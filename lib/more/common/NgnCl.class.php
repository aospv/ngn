<?php

/**
 * Comman Line
 *
 */
class NgnCl {

  static public function strParamsToArray($s) {
    $options = array();
    if (strstr($s, '=')) {
      $argv3 = str_replace('+', '&', $s);
      parse_str($argv3, $options);
      foreach ($options as $k => $v) $v[$k] = Arr::formatValue2($v[$k]);
    }
    return $options;
  }
  
  static public function arrayToStrParams(array $a) {
    $r = array();
    foreach ($a as $k => $v) {
      $r[] = $k.'='.$v;
    }
    return implode('+', $r);
  }
  
  static public function parseArgv(array $argv, array &$options) {
    foreach ($argv as $arg) {
      if (substr($arg, 0, 2) == '--' and isset($options[substr($arg, 2)])) {
        $options[substr($arg, 2)] = true;
      }
    }
  }

}
