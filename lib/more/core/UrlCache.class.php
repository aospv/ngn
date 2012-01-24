<?php

class UrlCache {

  static public $fCache = false;
  static public $fCacheWPath;
  static public $fCachePath;
  
  static public function get($url, $ext = '') {
    return self::$fCacheWPath.'/'.self::make($url, $ext);
  }
  
  static public function stripDomainAndProtocol($url) {
    return preg_replace('/[a-z]+:\/\/[a-z][a-z0-9-.]*\/(.*)/i', '$1', $url);
  }
  
  static public function make($url, $ext = '') {
    if (strstr($url, '_')) throw new NgnException('$url cant contain "_" symbol');
    if (!strstr($url, 'http://'))
      $url = 'http://'.SITE_DOMAIN.'/'.Misc::clearFirstSlash($url);
    $filename = str_replace('/', '_', self::stripDomainAndProtocol($url)).
      ($ext ? '.'.$ext : '');
    if (
    !self::$fCache or 
    !file_exists(self::$fCachePath.'/'.$filename)
    ) {
      Dir::make(self::$fCachePath);
      copy($url, self::$fCachePath.'/'.$filename);
    }
    return $filename;
  }
  
  static public function clearCache() {
    Dir::clear(self::$fCachePath);
  }

}

UrlCache::$fCache = Config::getVarVar('url', 'cache');
UrlCache::$fCacheWPath = UPLOAD_DIR.'/url-cache';
UrlCache::$fCachePath = UPLOAD_PATH.'/url-cache';
