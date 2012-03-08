<?php

abstract class CtrlPageVStoreOrderAbstract extends CtrlPage {

  static public function getVirtualPage() {
    return array(
      'title' => 'Заказ'
    );
  }
  
  protected $cartItems;
  
  protected function initCartItems() {
    $this->cartItems = StoreCart::get()->getItems();
  }

  public function action_default() {
    $this->initCartItems();
    if (!$this->cartItems) {
      $this->d['tpl'] = 'pageModules/storeOrder/empty';
      return;
    }
    if ($this->processForm(new StoreOrderForm($this->cartItems))) {
      StoreCart::get()->clear();    	
      $this->redirect(Tt::getPath(1).'/complete');
    }
  }

  public function action_complete() {
    $this->d['tpl'] = 'pageModules/storeOrder/complete';
  }
  
  //public function action_ajax_add() {
  //  StoreCart::get()->clear()->add($this->oReq->rq('pageId'), $this->oReq->rq('itemId'));
  //}
  
}