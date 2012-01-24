<?php

class SiteConfig {

  static public $varsFolder;

  static function updateConstant($name, $k, $v, $formatValue = true) {
    Config::updateConstant(SITE_PATH.'/config/constants/'.$name.'.php', $k, $v,
      $formatValue);
  }

  static function updateConstants($name, $constants, $formatValue = true) {
    Config::updateConstants(SITE_PATH.'/config/constants/'.$name.'.php', 
      $constants, $formatValue);
  }

  static function addConstant($name, $k, $v) {
    Config::addConstant(SITE_PATH.'/config/constants/'.$name.'.php', $k, $v);
  }

  static function addConstants($name, $constants) {
    foreach ($constants as $k => $v)
      Config::addConstant(SITE_PATH.'/config/constants/'.$name.'.php', 
        $k, $v);
  }

  static function deleteConstant($name, $k) {
    Config::deleteConstant(SITE_PATH.'/config/constants/'.$name.'.php', $k);
  }

  static function replaceConstant($name, $k, $v) {
    Config::replaceConstant(SITE_PATH.'/config/constants/'.$name.'.php', $k, $v);
  }
  
  static function replaceConstants($name, $constants) {
    Config::replaceConstants(SITE_PATH.'/config/constants/'.$name.'.php', $constants);
  }
  
  static public function createConstants($name, $constants) {
    Config::createConstants(SITE_PATH.'/config/constants/'.$name.'.php', $constants);
  }
  
  static function cleanupConstants($name) {
    Config::cleanupConstants(SITE_PATH.'/config/constants/'.$name.'.php');
  }

  static function getConstants($name, $quietly = false) {
    return Config::getConstants(SITE_PATH.'/config/constants/'.$name.'.php', $quietly);
  }

  static function getNgnConstants($name) {
    return Config::getConstants(NGN_PATH.'/config/constants/'.$name.'.php');
  }

  static function getConstant($name, $k) {
    return Config::getConstant(SITE_PATH.'/config/constants/'.$name.'.php', $k);
  }

  static function getNgnConstant($name, $k) {
    return Config::getConstant(NGN_PATH.'/config/constants/'.$name.'.php', $k);
  }

  static function getAllConstants() {
    return Config::getAllConstants(SITE_PATH.'/config/constants');
  }

  static function updateVar($k, $v) {
    $v = Arr::transformValue($v);
    Config::updateVar(self::$varsFolder."/$k.php", $v);
  }

  static function getVarConfigs() {
    return Config::getVarConfigs(self::$varsFolder);
  }

  static function getVars() {
    if (Misc::isGod())
      return Config::getVars(self::$varsFolder);
    if (! $allowed = self::getVar('allowedConfigVars', true))
      return false;
    foreach (Config::getVars(self::$varsFolder) as $k => $v) {
      if (in_array($k, $allowed))
        $r[$k] = $v;
    }
    return $r;
  }
  
  static public function updateSubVar($name, $subKey, $value) {
    Config::updateSubVar(self::$varsFolder."/$name.php", $subKey, $value);
  }

  static function getConfigFiles() {
    if (Misc::isGod())
      return Config::getVars(self::$varsFolder);
    if (! $allowed = self::getVar('allowedConfigVars', true))
      return false;
    foreach (Config::getVars(self::$varsFolder) as $k => $v) {
      if (in_array($k, $allowed))
        $r[$k] = $v;
    }
    return $r;
  }

  static function getNames($type) {
    return Config::_getVars(SITE_PATH.'/config/'.$type, false);
  }

  static function getTitles($type) {
    $structs = self::getStruct($type);
    foreach ($structs as $name => $struct) {
      $r[$name] = isset($struct['title']) ? $struct['title'] : $name;
    }
    return $r;
  }

  /**
   * Возвращает массив с существующими структурами конфигурационных констант или переменных
   *
   * @param   string  "constants" / "vars"
   * @return  array
   */
  static function getStruct($type) {
    $struct = Config::getStruct(NGN_PATH, $type);
    $struct += Config::getStruct(SITE_PATH, $type);
    return $struct;
  }
  
  static public function deleteVarSection($name) {
    File::delete(self::$varsFolder.'/'.$name.'.php');
  }
  
  static public function hasSiteVar($name) {
    return file_exists(self::$varsFolder.'/'.$name.'.php');
  }
  
  static public function renameVar($prefix, $from, $to, $strict = true) {
    foreach (glob(self::$varsFolder.'/'.$prefix.'*') as $file) {
      if (!$strict and !file_exists($file)) continue;
      rename($file, str_replace($from, $to, $file));
    }
  }
  
}

SiteConfig::$varsFolder = SITE_PATH.'/config/vars';
