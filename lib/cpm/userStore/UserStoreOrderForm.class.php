<?php

class UserStoreOrderForm extends Form {

  protected $storeUserId;
  
  public function __construct($storeUserId) {
    $oF = new UserStoreCustomerFields($storeUserId);
    // Добавляем список текущих товаров для заказа в первую колонку полей
    $oF->fields = Arr::injectAfter($oF->fields, 'col1', array(
      'name' => 'orderItems',
      'text' => '<p><b>Вы покупаете следующие товары:</b></p>'.
        Tt::getTpl('userStoreOrder/cartProducts', StoreCart::get()->getItems()),
      'type' => 'staticText',
    ), 'name');
    parent::__construct($oF, array(
      'submitTitle' => 'Отправить заказ'
    ));
    $this->storeUserId = $storeUserId;
    $this->addVisibilityCondition('col2', 'deliveryWay', 'v != "self"');
  }
  
  protected function _update(array $data) {
    if (!($ids = StoreCart::get()->getIds())) return;
    $orderId = DbModelCore::create('userStoreOrder', array(
      'userId' => $this->storeUserId,
      'data' => $data
    ));
    foreach ($ids as $v) {
      $v['orderId'] = $orderId;
      db()->create('userStoreOrderItems', $v);
    }
    /*
    O::get('SendEmail')->send(
      DbModelCore::get('users', $this->storeUserId)->r['email'],
      'Новый заказ',
      getPrr($data)
    );
    */
  }

}