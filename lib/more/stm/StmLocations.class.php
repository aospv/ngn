<?php

class StmLocations {
  
  static $locations;
  
  static public function init() {
    self::$locations = array(
      'ngn' => array(
        'canEdit' => false,
        'title' => 'Предустановленные',
        'themeFolder' => STM_DESIGN_PATH,
        'menuFolder' => STM_MENU_PATH
      ),
      'site' => array(
        'canEdit' => true,
        'title' => 'Проект',
        'themeFolder' => STM_DATA_PATH.'/design',
        'menuFolder' => STM_DATA_PATH.'/menu'
      )
    );  	
  }

  static public function getThemeFolders() {
    return Arr::get(self::$locations, 'themeFolder', 'KEY');
  }
  
  static public function getMenuFolders() {
    return Arr::get(self::$locations, 'menuFolder', 'KEY');
  }
  
} StmLocations::init();
