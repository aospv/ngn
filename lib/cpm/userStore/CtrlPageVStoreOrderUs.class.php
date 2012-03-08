<?php

class CtrlPageVStoreOrderUs extends CtrlPageVStoreOrder {

  static $title = 'Заказ (пользовательский магазин)';

  protected function init() {
    parent::init();
    $this->storeUserId = (int)$this->getParam(1);
    Misc::checkEmpty($this->storeUserId);
  }
  
  public function action_default() {
    if ($this->processForm(new UserStoreOrderForm($this->storeUserId))) {
      $this->redirect(Tt::getPath(2).'/complete');
    }
  }
  
}
