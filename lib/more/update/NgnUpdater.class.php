<?php

class NgnUpdaterInside {
  
  static public $reposFolder = '/repos/ngn';
  
  private $url;
  
  public function __construct() {
    $this->url = 'http://'.UPDATER_URL.'/c2/updater';
  }
  public function getNewBuildN() {
    return file_get_contents($this->url.'?a=ajax_getNewBuildN');
  }
  
  public function update() {
    set_time_limit_q(0);
    copy('http://'.UPDATER_URL.$this->reposFolder.'/ngn.zip', self::$reposFolder.'ngn.zip');
    copy('http://'.UPDATER_URL.$this->reposFolder.'/updater', self::$reposFolder.'updater.php');
    include(WEBROOT_PATH.'/updater.php');
  }
  
}
