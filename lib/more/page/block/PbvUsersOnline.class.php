<?php

class PbvUsersOnline extends PbvAbstract {
  
  static public $cachable = false;
  
  public function _html() {
    return Tt::getTpl('common/usersOnline', $this->oPBM['settings']);
  }
  
}