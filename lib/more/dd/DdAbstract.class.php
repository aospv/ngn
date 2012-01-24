<?php

abstract class DdAbstract {

  protected $strname;

  public function __construct($strName) {
    Misc::checkEmpty($strName);
    $this->strName = $strName;
  }

}
