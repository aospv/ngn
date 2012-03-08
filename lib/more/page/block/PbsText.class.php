<?php

class PbsText extends PbsAbstract {

  static public $title = 'Текст';
  
  //protected $hasJsInit = true;
  
  protected function initDefaultFields() {
  }
  
  protected function initFields() {
    $this->fields[] = array(
      'title' => 'Текст',
      'name' => 'text',
      'type' => 'wisiwigSimple2',
    );
  }

}