<?php

class PbvCurSubPages extends PbvSubPagesAbstract {

  protected function getPageId() {
    isset($this->oCC->page['pathData'][1]) ?
      $this->oCC->page['pathData'][1]['id'] : false;
  }
  
}