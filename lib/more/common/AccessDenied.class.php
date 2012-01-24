<?php

class AccessDenied extends NgnException {

  public function __construct($title = 'Доступ запрещён') {
    parent::__construct($title);
  }

}
