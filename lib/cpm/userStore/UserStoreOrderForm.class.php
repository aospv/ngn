<?php

class UserStoreOrderForm extends StoreOrderFormBase {

  protected $storeUserId;
  
  public function __construct($storeUserId) {
    $oF = new UserStoreCustomerFields($storeUserId);
    $this->storeUserId = $storeUserId;
    // Добавляем список текущих товаров для заказа в первую колонку полей
    $oF->fields = Arr::injectAfter($oF->fields, 'col1', $this->getCartProductsField(), 'name');
    parent::__construct($oF, array(
      'submitTitle' => 'Отправить заказ'
    ));
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