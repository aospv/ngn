<?php

class PageBlockStructure_tpl extends PageBlockStructureAbstract {

  static public $cachable = false;
  
  static public $title = 'Шаблон';
  
  protected function initFields() {
    $this->fields = array(
      array(
        'title' => 'Имя шаблона',
        'name' => 'name',
        'type' => 'name',
        'required' => true
      )
    );
  }
  

}