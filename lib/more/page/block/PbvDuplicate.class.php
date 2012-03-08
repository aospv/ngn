<?php

class PbvDuplicate extends PbvAbstract {

  /**
   * Duplicate model
   * 
   * @var DbModel
   */
  protected $oPBDM;

  protected function init() {
    $this->oPBDM = DbModelCore::get('pageBlocks', $this->oPBM['settings']['duplicateBlockId']);
  }
  
  public function _html() {
    return O::get(ClassCore::nameToClass('Pbv', $this->oPBDM->type), $this->oPBDM)->_html();
  }
  
  public function getData() {
  }

}