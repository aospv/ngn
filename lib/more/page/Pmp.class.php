<?php

/**
 * Page Module Priviliges
 */
abstract class Pmp extends NgnArrayAccess {

  protected $userId;

  public function __construct($userId) {
    $this->userId = $userId;
    $this->init();
  }
  
  abstract protected function init();

}
