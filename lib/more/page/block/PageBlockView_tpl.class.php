<?php

class PageBlockView_tpl extends PageBlockViewAbstract {

  public function html() {
    return Tt::getTpl('pageBlocks/tpl/'.$this->oPBM['settings']['name']);
  }

}
