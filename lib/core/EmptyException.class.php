<?php

class EmptyException extends NgnException {

  public function __construct($varName, $code = 666, $previous = null) {
    parent::__construct('"'.$varName.'" can not be empty', $code, $previous);
  }

}
