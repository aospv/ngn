<?php

class PbsFutureItems extends PbsItems {
  
  static public $title = 'Записи в будующем';
  
  protected function initFields() {
    parent::initFields();
    $this->fields[] = array(
      'name' => 'dateField',
      'title' => 'Поле даты',
      'type' => 'ddDateFields',
      'required' => true
    );
  }

}
