<?php

class FieldEBoolCheckbox extends FieldECheckbox {

  public $options = array(
    'type' => 'checkbox',
    'noTitle' => true,
    'value' => 0
  );
  
  protected function init() {
    $this->options['options'] = array(1 => $this->options['title']);
    $this->options['value'] = (bool)$this->options['value'];
    parent::init();
  }
  
  /**
   * В валидации на "empty" не нуждается. Значение явно приводится к 0 или 1
   */
  protected function validate1() {}
  
  public function isEmpty() {
    return false;
  }

}