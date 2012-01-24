<?php

class FieldESubmit extends FieldEInput {

  public $inputType = 'submit';
  
  public $options = array(
    'noTitle' => true,
    'noValue' => true,
    'type' => 'submit'
  );
  
  public function value() {
    return null;
  }

}
