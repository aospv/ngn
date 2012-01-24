<?php

define('NGN_ENV_PATH', dirname(NGN_PATH));

if (!defined('NGN_ENV_PATH'))
  throw new NgnException('Constant "NGN_ENV_PATH" must be defubed');
  
class CurrentSiteBackup {

  static $path;
  static $tempPath;
  static $maxBackups;
  static $dir;
  
  static public function init() {
    self::$path = NGN_ENV_PATH.'/backup/'.SITE_DOMAIN;
    self::$tempPath = NGN_ENV_PATH.'/temp/backupRestore/'.SITE_DOMAIN;
    self::$maxBackups = 3;
  }
  
  static public function make() {
    $dirs = Dir::dirs(self::$path);
    $lastId = Arr::last($dirs);
    if ($lastId >= self::$maxBackups) Dir::remove(self::$path.'/'.$dirs[0]);
    $backupDir = self::$path.'/'.($lastId+1);
    Dir::make($backupDir);
    // ---------------------------------
    Zip::dir($backupDir.'/files.zip', WEBROOT_PATH);
    O::get('DbDumper', db())->createDump($backupDir.'/db.sql');
    Zip::file($backupDir.'/db.zip', $backupDir.'/db.sql');
    unlink($backupDir.'/db.sql');
  }
    
  static public function getList() {
    $r = array();
    foreach (Dir::dirs(self::$path) as $v) {
      $r[] = array(
        'id' => $v,
        'time' => filemtime(self::$path.'/'.$v)
      );
    }
    return $r;
  }
  
  static public function restore($id) {
    $backupDir = self::$path.'/'.$id;
    if (!file_exists($backupDir))
      throw new NgnException('Backup folder "'.self::$path.'/'.$id.'" does not exists');
    Dir::make(self::$tempPath);
    Zip::extract($backupDir.'/files.zip', self::$tempPath);
    Zip::extract($backupDir.'/db.zip', self::$tempPath);
    $dirs = Dir::dirs(self::$tempPath);
    Dir::moveContents(self::$tempPath.'/'.$dirs[0], WEBROOT_PATH);
    db()->delete();
    db()->importFile(self::$tempPath.'/db.sql');
    Dir::clear(self::$tempPath);
  }
  
  static public function delete($id) {
    Dir::remove(self::$path.'/'.$id);
  }
  
} CurrentSiteBackup::init();

