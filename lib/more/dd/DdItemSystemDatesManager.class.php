<?php

class DdItemSystemDatesManager extends DbItemsManager {

  public function __construct($strName, $itemId) {
    parent::__construct(new DbItems(DdCore::table($strName)), new Form(new Fields(array(
      array(
        'title' => 'Дата создания',
        'type' => 'datetime',
        'name' => 'dateCreate'
      ),
      array(
        'title' => 'Дата пубикации',
        'type' => 'datetime',
        'name' => 'datePublish'
      ),
      array(
        'title' => 'Дата изменения',
        'type' => 'datetime',
        'name' => 'dateUpdate'
      ),
      array(
        'title' => 'Дата последнего комментавия',
        'type' => 'datetime',
        'name' => 'commentsUpdate'
      ),
    ))));
    $this->oForm->setElementsData($this->oItems->getItem($itemId));
  }

}