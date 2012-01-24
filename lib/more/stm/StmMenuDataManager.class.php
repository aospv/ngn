<?php

class StmMenuDataManager extends StmDataManager {

  protected $requiredOptions = array('location', 'id');
  
  protected function defineOptions() {
    $this->options['type'] = 'menu';
    $this->options['subType'] = 'menu';
  }
  
}
