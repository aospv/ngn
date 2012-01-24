<?php

class FieldEHtml extends FieldEAbstract {

  protected $requiredOptions = array('html');
  
  public $options = array(
    'noRowHtml' => true,
    'noValue' => true
  );
  
  public function _html() {
    return $this->options['html'];
  }

}
