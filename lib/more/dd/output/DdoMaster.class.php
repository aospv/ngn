<?php

class DdoMaster extends Ddo {
  
  protected function initTpls() {
    parent::initTpls();
    $this->ddddByName['title'] = 
      'getPrr($o->pageSettings)';
  }
  
}
