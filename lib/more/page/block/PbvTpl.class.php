<?php

class PbvTpl extends PbvAbstract {

  public function _html() {
    return Tt::getTpl('pageBlocks/tpl/'.$this->oPBM['settings']['name']);
  }

}
