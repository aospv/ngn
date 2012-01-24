<?php

class CronJobGrabber extends CronJobAbstract {
  
  public function __construct() {
    $this->period = Config::getVarVar('grabber', 'period', true);
  }
  
  public function _run() {
    $n = Grabber::import();
    print "Импортировано $n записей";
  }
  
}