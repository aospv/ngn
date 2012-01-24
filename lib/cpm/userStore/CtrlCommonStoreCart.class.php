<?php

class CtrlCommonStoreCart extends CtrlCommon {

  public function action_ajax_add() {
    StoreCart::get()->add($this->oReq->rq('pageId'), $this->oReq->rq('itemId'));
  }
  
  public function action_ajax_delete() {
    StoreCart::get()->delete($this->oReq->rq('pageId'), $this->oReq->rq('itemId'));
  }
  
}