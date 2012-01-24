<?php

class SubPaTagsTree extends SubPa {

  /**
   * @return DdTagsTagsBase
   */
  protected function getTags() {
    $oTags = DdTags::getByGroupId($this->oPA->getParam(1));
    return $oTags;
  }
	
  public function action_ajax_move() {
    $this->getTags()->move($this->oPA->oReq->rq('id'), $this->oPA->oReq->rq('toId'), $this->oPA->oReq->rq('where'));
  }

  public function action_ajax_rename() {
    DbModelCore::update('tags', $this->oPA->oReq->rq('id'), array('title' => $this->oPA->oReq->rq('title')));
  }

  public function action_ajax_delete() {
    DdTags::deleteById($this->oPA->oReq->rq('id'));
  }
  
  public function action_json_create() {
    $this->oPA->json = DdTags::getById(
      $this->getTags()->create(array(
        'title' => $this->oPA->oReq->rq('title'),
        'parentId' => $this->oPA->oReq->rq('parentId'),
        'userGroupId' => $this->oPA->userGroup['id']
      ))
    ); 
  }
  
  public function action_json_move() {
    $this->oPA->json = O::get('MifTree')->setData($this->getTags()->getTree())->getTree();
  }
  
  public function action_json_getTree() {
    $this->oPA->json = O::get('MifTree')->setData($this->getTags()->getTree())->getTree();
  }

}
