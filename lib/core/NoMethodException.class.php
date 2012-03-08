<?php

class NoMethodException extends NgnException {

  public function __construct($method, $code = 666, $previous = null) {
    parent::__construct('Method "'.$method.'" does not exists', $code, $previous);
  }

}