<?php

class CtrlPageVStoreOrderHp extends CtrlPageVStoreOrder {

  static $title = 'Заказ (скрытая цена)';

  protected function initCartItems() {
    parent::initCartItems();
    if ($this->cartItems)
      if (Auth::get('id'))
        foreach ($this->cartItems as &$v) $v['price'] = $v['price2'];
  }
  
}
