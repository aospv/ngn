<?php


// получить список главных шаблонов

class Tpl {
  
  static public $master;
	
  static public function getSettingsFields($path) {
    preg_match(
      '/@tplSettings:(.*)\*\/\?>(.*)/U',
      str_replace("\n", '', file_get_contents(Tt::exists($path))),
      $m
    );
    return eval('return '.$m[1].';');
  }
  
  static public function saveSettings($path, $settings) {
    Settings::set('tplSettings.'.self::clearSlashes($path), $settings);
  }
  
  static public function getSettings($path) {
    return Settings::get('tplSettings.'.self::clearSlashes($path));
  }
  
  static public function clearSlashes($path) {
    return str_replace('/', '~', $path);
  }
  
  static public function returnSlashes($path) {
    return str_replace('~', '/', $path);    
  }
  
  static public function getList($masterFolder, $parentPath = null) {
    $list = array();
    $tplFolder = $masterFolder.'/'.$parentPath;
    if (!$dir = dir($tplFolder)) {
      return $list;
    }
    while (false !== $entry = $dir->read()) {
      if ($entry[0] == '.') continue;
      if (is_dir($tplFolder.'/'.$entry)) {
        $list = Arr::append($list, self::getList(
          $masterFolder,
          $parentPath ? ($parentPath.'/'.$entry) : $entry
        ));
      } elseif (preg_match('/(.*).php/', $entry, $m)) {
        $list[] = ($parentPath ? $parentPath.'/' : '').$m[1];
      }
    }
    return $list;
  }
  
  static public function getListNGN() {
    return self::getList(NGN_PATH.'/tpl');
  }

  static public function getListMaster() {
    return self::getList(MASTER_PATH.'/tpl');
  }
  
  static public function getListTheme() {
    return self::getList(TPL_THEMES_PATH.'/'.TPL_THEME);
  }
  
  static public function getListSite() {
    return self::getList(SITE_PATH.'/tpl');
  }
  
  static public function setMaster($master) {
    self::$master = $master;
  }
  
  static public function setSlave($master) {
    self::$master = $master;
  }
  
}
