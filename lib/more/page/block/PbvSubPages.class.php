<?php

class PbvSubPages extends PbvSubPagesAbstract {

  protected function getPageId() {
    return $this->oPBM['settings']['pageId'];
  }

}