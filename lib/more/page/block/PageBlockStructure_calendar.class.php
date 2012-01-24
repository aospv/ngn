<?php

class PageBlockStructure_calendar extends PageBlockStructureDdPage {

  static public $title = 'Календарь';
  
  protected function initDefaultFields() {
  }
  
  protected function initFields() {
    $this->fields[] = array(
      'name' => 'dateField',
      'title' => 'Поле даты',
      'type' => 'ddDateFields',
      'required' => true
    );
  }

}