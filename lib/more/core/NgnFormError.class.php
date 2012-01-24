<?php

class NgnFormError extends NgnValidError {

  public $elementName;
  
  public function __construct($elementName, $message, $code = 123, $previous = null) {
    $this->elementName = $elementName;
    parent::__construct($message, $code, $previous);
  }

}