<?php

class PageBlockView_text extends PageBlockViewAbstract {
  
  static public $cachable = true;
  
  public function html() {
    return $this->oPBM['settings']['text'];
  }
  
}