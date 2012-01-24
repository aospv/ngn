<?php

class PageBlockView_usersOnline extends PageBlockViewAbstract {
  
  static public $cachable = false;
  
  public function html() {
    return Tt::getTpl('common/usersOnline', $this->oPBM['settings']);
  }
  
}