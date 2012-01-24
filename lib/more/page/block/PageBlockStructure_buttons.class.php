<?php

class PageBlockStructure_buttons extends PageBlockStructureAbstract {

  static public $title = 'Кнопки';
  
  protected function initFields() {
    $this->fields = array(
      array(
        'title' => 'Конпки',
        'name' => 'buttons', 
        'type' => 'fieldSet', 
        'fields' => array(
          array(
            'title' => 'Текст',
            'name' => 'title'
          ), 
          array(
            'title' => 'Ссылки',
            'name' => 'link'
          )
        )
      )
    );
  }

}