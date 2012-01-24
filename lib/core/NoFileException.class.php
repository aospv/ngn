<?php

class NoFileException extends NgnException {

  public function __construct($file, $code = 777, $previous = null) {
    parent::__construct('File "'.$file.'" does not exists', $code, $previous);
  }

}
