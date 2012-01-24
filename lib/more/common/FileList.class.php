<?php

class FileList {
  
  static public function get($file) {
    if (!file_exists($file)) return array();
    return explode("\n", trim(file_get_contents($file)));
  }
  
  static public function replace($file, array $data) {
    file_put_contents($file, implode("\n", $data));
  }
  
  static public function merge($file, array $data) {
    file_put_contents($file, implode("\n", array_merge(self::get($file), $data)));
  }
  
  static public function addItem($file, $item) {
    self::merge($file, array($item));
  }
  
}
