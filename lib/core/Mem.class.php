<?php

class Mem {
  
  static $keyPrefix = '';
  static $initialized = false;
  static $m;
  static $enable = false;
  
  /**
   * @return Memcache
   */
  static public function getMemcache() {
    if (!static::$initialized) {
      static::init();
      static::$initialized = true;
    }
    if (isset(self::$m)) return self::$m;
    self::$m = new Memcache;
    if (!self::$m->connect('localhost', 11211))
      throw new NgnException("Could not connect to memcached");
    return self::$m;
  }
  
  static protected function init() {} 
  
  static public function get($key) {
    if (!self::$enable) return false;
    return self::getMemcache()->get(static::$keyPrefix.$key);
  }
  
  static public function set($key, $val, $expires = 0) {
    if (!self::$enable) return;
    self::getMemcache()->set(static::$keyPrefix.$key, $val, false, $expires);
  }
  
  static public function delete($key) {
    if (!self::$enable) return;
    self::getMemcache()->delete(static::$keyPrefix.$key);
  }
  
  static public function setIfNotExists($key, $val, $expires = 60) {
    if (self::get($key) !== false)
      throw new NgnException("$key already exists.");
    self::set($key, $val, $expires);
  }
  
  static public function getAndDelete($key) {
    $v = self::get($key);
    self::delete($key);
    return $v;
  }
  
  static public function clean() {
    if (!self::$enable) return;
    self::getMemcache()->flush();
  }

} Mem::$enable = function_exists('memcache_connect');
