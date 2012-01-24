<?php

/**
 * Data Manager Field Action
 */
abstract class Dmfa {
  
  /**
   * @var DataManagerAbstract
   */
  protected $oDM;
  
  public function __construct(DataManagerAbstract $oDM) {
    $this->oDM = $oDM;
  }
  
  /**
   * В чем разница между?
   * form2sourceFormat и beforeCreateUpdate
   */
  
  // public function form2sourceFormat($v) { return $v }
  // public function source2formFormat($v) { return $v }
  // public function beforeCreateUpdate(FieldEAbstract $el) {}
  // public function afterCreateUpdate(FieldEAbstract $el) {}
  // public function afterUpdate(FieldEAbstract $el) {}
  // public function beforeDelete(FieldEAbstract $el) {}

}