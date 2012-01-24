<?php

class CronJobNotify extends CronJobAbstract {

  public $period = 'every10min';
  
  public function __construct() {
    $this->enabled = Config::getVarVar('notify', 'enable', true);
  }
  
  public function _run() {
    $n = Notify_Send::send();
    print "Выслано уведомлений: $n";
  }
  
}
