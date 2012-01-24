<?php

class PagePrivException extends NgnException {

  public function __construct($priv, $code = 663, $previous = null) {
    parent::__construct("Privilege '$priv' not allowed", $code, $previous);
  }

}