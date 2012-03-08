<?php

class PbvText extends PbvAbstract {
  
  static public $cachable = true;
  
  public function _html() {
    return $this->oPBM['settings']['text'];
  }
  
}