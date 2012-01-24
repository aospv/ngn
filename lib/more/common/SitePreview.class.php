<?php

class SitePreview {

  const folder = 'sitePreview';
  
  static public $sizes = array('smW' => 200, 'smH' => 100);
  
  static protected function filePath() {
    return UrlCache::$fCachePath.'/'.self::folder.'/'.self::$filename;
  }
  
  static protected $filename;
  
  static protected function _file($_url) {
    self::$filename = Misc::translate(str_replace('http://', '', Misc::clearLastSlash($_url))).'.png';
    $url = Config::getVarVar('url', 'sitePreviewUrl').'?url='.$_url.'&forceCache=1';
    if (!Url::exists($url)) {
      return false;
    }
    Dir::make(UrlCache::$fCachePath.'/'.self::folder);
    //File::delete(self::filePath());
    output($url);
    copy($url, self::filePath());
    // ----- thumb ------
    require_once VENDORS_PATH.'/wideimage/WideImage.php';
    //$smFile = ;
    //File::delete($smFile);
    WideImage::load(self::filePath())->
      resize(self::$sizes['smW'], 100000)->
      crop('center', 'top', self::$sizes['smW'], self::$sizes['smH'])->
      saveToFile(Misc::getFilePrefexedPath(self::filePath(), 'sm_'));
    return true;
  }

  // ------------------
  
  static public function url($_url) {
    if (!self::_file($_url))
      return '/'.STATIC_DIR.'/img/page-not-available.gif';
    return '/'.UrlCache::$fCacheWPath.'/'.self::folder.'/sm_'.self::$filename;
  }
  
  static public function file($_url) {
    if (!self::_file($_url)) return false;
    return self::filePath();
  }

}
