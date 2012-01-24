<?php

/**
 * DD tags autocomplete
 */
class CtrlCommonDdTagsAc extends CtrlCommon {

  public function action_default() {
    $this->isJson = true;
    $items = db()->query(
      'SELECT id, title FROM tags WHERE groupName=? AND strName=? AND title LIKE ?',
      $this->oReq->rq('fieldName'), $this->oReq->rq('strName'), str_replace('%', '', $this->oReq->rq('search')).'%');
    $this->json = array();
    foreach ($items as $v) {
      $this->json[] = array(
        $v['title'],
        $v['title'],
        $v['title'],
      );
    }
  }

}
