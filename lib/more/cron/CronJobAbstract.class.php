<?php

abstract class CronJobAbstract {

  public $period;
  public $enabled = true;
  
  abstract public function _run();
  
  public function run() {
    ob_start();
    $this->_run();
    $s = ob_get_contents();
    ob_end_clean();
    return $s;
  }
  
}
