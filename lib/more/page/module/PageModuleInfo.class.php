<?php

class PageModuleInfo {

  public $module, $folderPath, $folderWpath;

  static $folders = array();
  
  public function __construct($module) {
    $this->module = $module;
    $this->initFolderPaths();
  }
  
  static public function addFolder($path, $wPath) {
    self::$folders[] = array($path, $wPath);
  }

  protected function initFolderPaths() {
    foreach (PageModuleCore::getAncestorNames($this->module) as $_module)
      if ($this->_setFolderPaths($_module))
        return;
    throw new NgnException("No such module as '{$this->module}'");
  }
  
  public function getFilePaths($filename) {
    foreach (PageModuleCore::getAncestorNames($this->module) as $module) {
      if (file_exists(CORE_PAGE_MODULES_PATH.'/'.$module.'/'.$filename)) {
        return array(
          CORE_PAGE_MODULES_PATH.'/'.$module.'/'.$filename,
          CORE_PAGE_MODULES_DIR.'/'.$module.'/'.$filename
        );
      } elseif (file_exists(SITE_PAGE_MODULES_PATH.'/'.$module.'/'.$filename)) {
        return array(
          SITE_PAGE_MODULES_PATH.'/'.$module.'/'.$filename,
          SITE_PAGE_MODULES_DIR.'/'.$module.'/'.$filename
        );
      }
    }
    return false;
  }
  
  protected function _setFolderPaths($module) {
    if (file_exists(CORE_PAGE_MODULES_PATH.'/'.$module)) {
      $this->folderPath = CORE_PAGE_MODULES_PATH.'/'.$module;
      $this->folderWpath = CORE_PAGE_MODULES_DIR.'/'.$module;
      return true;
    } elseif (file_exists(SITE_PAGE_MODULES_PATH.'/'.$module)) {
      $this->folderPath = SITE_PAGE_MODULES_PATH.'/'.$module;
      $this->folderWpath = SITE_PAGE_MODULES_DIR.'/'.$module;
      return true;
    } else {
      return false;
    }
  }
  
  public function get($name) {
    return file_exists($this->folderPath.'/'.$name.'.php') ?
      include $this->folderPath.'/'.$name.'.php' : false;
  }

}

PageModuleInfo::addFolder(CORE_PAGE_MODULES_PATH, CORE_PAGE_MODULES_DIR);