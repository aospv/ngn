<?php

class CtrlCommonDdTagsConsecutiveSelect extends CtrlCommon {

  public $paramActionN = 3;

  public function action_ajax_default() {
    $oTags = new DdTagsTagsTree(new DdTagsGroup($this->getParam(2), $this->oReq->r['name']));
    if (!($tags = $oTags->getTags($this->oReq->r['id']))) return;
    Tt::tpl('dd/consecutiveSelectAjax', array(
      'name' => $this->oReq->r['name'],
      'options' =>
        array('' => '—') +
        Arr::get($tags, 'title', 'id')
    ));
  }

}
