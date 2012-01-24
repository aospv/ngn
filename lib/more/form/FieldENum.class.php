<?php

class FieldENum extends FieldEText {

  public $options = array(
    'cssClass' => 'validate-integer'
  );
  
  protected function prepareValue() {
    if (!empty($this->options['value']))
      $this->options['value'] = (int)$this->options['value'];
  }

}
