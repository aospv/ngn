<?php

class FieldEEmpty extends FieldEAbstract {

  public $options = array(
    'noRowHtml' => true,
    'noValue' => true
  );
  
  public function _html() {
    return '';
  }
  
}
