<?php

class DdFieldItems extends DbItems {

  public function __construct($strName) {
    parent::__construct('dd_fields');
    $this->cond->addF('strName', $strName);
  }

  public function getItems() {
    $items = parent::getItems();
    array_walk($items, function(&$v) {
      $v['isTagType'] = DdTags::isTagType($v['type']);
      $v['tagsGroupId'] =  db()->selectCell(
        'SELECT id FROM tags_groups WHERE strName=? AND name=?', $v['strName'], $v['name']);
    });
    return $items;
  }

}
