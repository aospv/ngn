<?php

class PmiEmpty extends Pmi {

  public $title = 'Пустой';
  
  public function __construct() {
    parent::__construct();
    $this->module = '';
  }

}