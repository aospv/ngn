<?php

class PageBlockStructure_text extends PageBlockStructureAbstract {

  static public $title = 'Текст';
  
  protected $hasJsInit = true;
  
  protected function initDefaultFields() {
  }
  
  protected function initFields() {
    $this->fields[] = array(
      'title' => 'Текст',
      'name' => 'text',
      'type' => 'wisiwig',
      //'resizeble' => false
    );
  }

}