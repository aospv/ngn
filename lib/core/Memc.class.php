<?php

/**
 * Memcached critical
 */
class Memc {

  static public function get($k) {
    if (!Mem::$enable) return false;
    if (($v = Mem::get($k)) !== false) return $v;
    // Если ключа нет, ищем его в БД. Но нужно учесть, что его позже нужно обязательно
    // сохранить в memcached
    if (($v = db()->selectCell('SELECT v FROM memcache WHERE k=?', $k)) === false)
      return false;
    return unserialize($v);
  }
  
  static public function set($k, $v) {
    if (!Mem::$enable) return;
    Mem::set($k, $v);
    db()->query('REPLACE INTO memcache SET k=?, v=?', $k, serialize($v));
  }
  
  static public function delete($k) {
    if (!Mem::$enable) return;
    Mem::delete($k);
    db()->query('DELETE FROM memcache WHERE k=?', $k);
  }
  
  static public function clean() {
    if (!Mem::$enable) return;
    Mem::clean();
    db()->query('TRUNCATE TABLE memcache');
  }
  
}