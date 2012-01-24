<?php

abstract class DdImportDataReceiver {
  
  /**
   * @var DdImportField
   */
  protected $oF;
  
  protected $fieldTypes;
  
  public function __construct(DdImportField $oF) {
    $this->oF = $oF;
    $this->fieldTypes = Arr::get($this->oF->getFields(), 'type', 'name');    
  }
  
  /**
   * @return DdImportField
   */
  public function getFieldObj() {
    return $this->oF;
  }
  
  abstract public function getData();
  
}
