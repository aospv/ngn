<?php

class PageBlockTypeForm extends Form {
  
  public function __construct(array $options = array()) {
    parent::__construct(new Fields(array(
      array(
        'name' => 'type',
        'title' => 'Тип',
        'type' => 'select',
        'options' => PageBlockCore::getTypeOptions(),
        'required' => true
      )
    )), $options);
  }
  
}