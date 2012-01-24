<?php

class CronJobLevelNominate extends CronJobLevel {
  
  public function _run() {
    $o = new LevelNominateManager();
    $o->nominate();    
  }
  
}