<?php

class StmMenuStructures {

  static $structures;
  
  static public function init() {
    foreach (Dir::dirs(STM_MENU_PATH) as $folder) {
      self::$structures[$folder] =
        include STM_MENU_PATH.'/'.$folder.'/structure.php';
    }
  }

} StmMenuStructures::init();
