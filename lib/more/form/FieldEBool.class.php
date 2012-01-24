<?php

class FieldEBool extends FieldERadio {

  public $options = array(
    'type' => 'radio',
    'options' => array(
      1 => 'Да',
      0 => 'Нет'
    )
  );
  
  protected function prepareValue() {
    if (!isset($this->options['value'])) {
      if (isset($this->options['default']))
        $this->options['value'] = (bool)$this->options['default'];
      else
        $this->options['value'] = false;
    } else {
      $this->options['value'] = (bool)$this->options['value'];
    }
  }
  
  /**
   * В валидации на "empty" не нуждается. Значение явно приводится к 0 или 1
   */
  protected function validate1() {}
  
  public function isEmpty() {
    return false;
  }

}