<?php

class FileSerialize {
  
  static public function get($file) {
    if (!file_exists($file)) return array();
    return unserialize(file_get_contents($file));
  }
  
  static public function replace($file, array $data) {
    file_put_contents($file, serialize($data));
  }
  
  static public function merge($file, array $data) {
    file_put_contents($file, serialize(array_merge(self::get($file), $data)));
  }
  
  static public function addItem($file, $item) {
    self::merge($file, array($item));
  }
  
}
