<?php

class PmsbPhoto extends PmsbAbstract {

  public function initBlocks() {
    if ($this->ctrl->action != 'showItem') return;
    $oItems = new DdItems($this->ctrl->page['id']);
    $oItems->addF(
      DdCore::masterFieldName,
      $this->ctrl->itemData[DdCore::masterFieldName]['id']
    );
    $cacher = new DdItemsCacher(
      $oItems,
      'block'
    );
    $this->addBlock(array(
      'colN' => 3,
      'type' => 'otherItems',
      'html' => $cacher->html()
    ));
  }

}