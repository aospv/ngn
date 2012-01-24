<?php

class PageBlockView_kwix extends PageBlockViewAbstract {

  public function html() {
    return Tt::getTpl('pageBlocks/kwix', $this->oPBM['settings']);
  }

}
