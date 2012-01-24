<?php

class DdFormBase extends Form {

  public $strName;

  public function __construct(Fields $oFields, $strName, array $options = array()) {
    $this->strName = $strName;
    parent::__construct($oFields, $options);
  }

}