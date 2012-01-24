<?php

class PageBlockView_futureItems extends PageBlockView_items {
  
  protected function initItems() {
    /* @var $oDdItems DdItems */
    $oDdItems = O::get('DdItems', $this->oPBM['settings']['pageId']);
    $oDdItems->cond->setLimit(!empty($this->oPBM['settings']['limit']) ?
      $this->oPBM['settings']['limit'] : 5);
    $oDdItems->cond->setOrder(!empty($this->oPBM['settings']['order']) ?
      $this->oPBM['settings']['order'] : 'dateCreate DESC');
    $oDdItems->addRangeFilter(
      $this->oPBM['settings']['dateField'],
      date('Y-m-d'),
      date('9000-01-01')
    );
    $this->items = $oDdItems->getItems();
  }

}
