<?php

class DbModelVirtual extends DbModel {

  public function __construct(array $r) {
    $this->r = $r;
  }

}