<?php

class Settings {

  static $table = 'settings';
  
  static function get($id, $returnFalseOnEmpty = false) {
    $r = db()->selectCell("SELECT ".static::$table." FROM settings WHERE id=?", $id);
    return empty($r) ? ($returnFalseOnEmpty ? false : array()) : unserialize($r);
  }
  
  static function getItems($prefix) {
    $r = array();
    foreach (db()->select(
    "SELECT id, settings FROM ".static::$table." WHERE id LIKE ?", $prefix.'%') as $v) {
      $r[str_replace($prefix, '', $v['id'])] = unserialize($v['settings']);
    }
    return $r;
  }
  
  static function exists($id) {
    return self::get($id) !== false;
  }
  
  static function set($id, $settings) {
    db()->query("REPLACE INTO ".static::$table." SET id=?, settings=?",
      $id, serialize($settings));
  }
  
  static function delete($id) {
    db()->query("DELETE FROM ".static::$table." WHERE id=?", $id);
  }
  
  static function remove($id, $k) {
    $s = self::get($id);
    unset($s[$k]);
    self::set($id, $s);
  }
  
  static function add($id, array $settings) {
    $s = self::get($id);
    self::set($id, array_merge($s, $settings));
  }
  
  static function addArray($id, array $settings) {
    if (($s = self::get($id)) === false) $s = array();
    self::set($id, Arr::append($s, $settings, true));
  }
  
}