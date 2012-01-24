<?php

class FieldEText extends FieldEInput {

  protected $requiredOptions = array('name');
  
  public $inputType = 'text';
  
  public $options = array(
    'maxlength' => 255,
    'required' => false,
    //'size' => 0
  );
  protected function prepareValue() {
    parent::prepareValue();
    if (!isset($this->options['value'])) $this->options['value'] = '';
  }
}
