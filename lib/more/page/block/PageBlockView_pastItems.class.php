<?php

class PageBlockView_pastItems extends PageBlockView_items {
  
  protected function initItems() {
    $this->oItems->cond->setLimit(!empty($this->oPBM['settings']['limit']) ?
      $this->oPBM['settings']['limit'] : 5);
    $this->oItems->cond->setOrder(!empty($this->oPBM['settings']['order']) ?
      $this->oPBM['settings']['order'] :
      'dateCreate DESC');
    $this->oItems->cond->addRangeFilter(
      $this->oPBM['settings']['dateField'],
      date('Y-m-d', time()-60*60*24*$this->oPBM['settings']['period']),
      date('Y-m-d')
    );
  }

}
