<?php

abstract class CronJobLevel extends CronJobAbstract {
  
  public $period = 'every1h';
  
  public function __construct() {
    $this->enabled = Config::getVarVar('level', 'on', true);
  }
    
} 
