<?php

class PageBlockView_duplicate extends PageBlockViewAbstract {

  /**
   * Duplicate model
   * 
   * @var DbModel
   */
  protected $oPBDM;

  protected function init() {
    $this->oPBDM = DbModelCore::get('pageBlocks', $this->oPBM['settings']['duplicateBlockId']);
  }
  
  public function html() {
    return O::get('PageBlockView_'.$this->oPBDM->type, $this->oPBDM)->html();
  }
  
  public function getData() {
    //$data = $this->oPBDM->r();
    //$data['id'] = $this->oPBM['id'];
    //return $data;
  }

}