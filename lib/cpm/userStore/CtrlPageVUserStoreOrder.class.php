<?php

class CtrlPageVUserStoreOrder extends CtrlPage {
  
  static public function getVirtualPage() {
    return array(
      'title' => 'Заказ'
    );
  }
  
  protected function initParamActionN() {
    $this->paramActionN = 2;
  }
  
  protected $storeUserId;
  
  protected function init() {
    parent::init();
    $this->storeUserId = (int)$this->getParam(1);
    Misc::checkEmpty($this->storeUserId);
  }
  
  public function action_default() {
    if (($this->d['items'] = StoreCart::get()->getItems()) === false)
      throw new AccessDenied('Корзина пуста');
    if ($this->processForm(new UserStoreOrderForm($this->storeUserId))) {
      $this->redirect(Tt::getPath(2).'/complete');
    }
  }
  
  public function action_complete() {
    $this->d['tpl'] = 'userStoreOrder/complete';
  }
  
  public function action_ajax_add() {
    StoreCart::get()->clear()->add($this->oReq->rq('pageId'), $this->oReq->rq('itemId'));
  }
  
}
