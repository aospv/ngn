<?php

class PageBlockView_curSubPages extends PageBlockView_subPagesAbstract {

  public function html() {
    if (empty($this->oCC)) return '';
    if (empty($this->oCC->page['pathData'][1])) return '';
    return parent::html();
  }
  
}