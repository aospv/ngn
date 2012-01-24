<?php

class UserStoreCustomerFields extends Fields {

  protected $storeUserId;

  public function __construct($storeUserId) {
    Misc::checkNumeric($storeUserId);
    $this->storeUserId = $storeUserId;
    $fields = array(
      array(
        'name' => 'col1',
        'type' => 'col',
      ),
      array(
        'title' => 'Способ доставки',
        'name' => 'deliveryWay',
        'required' => true,
        'type' => 'select',
        'options' => UserStoreCore::getDeliveryWays($this->storeUserId)
      ),
      array(
        'title' => 'Способ оплаты',
        'name' => 'paymentWay',
        'required' => true,
        'type' => 'select',
        'options' => UserStoreCore::getPaymentWays($this->storeUserId)
      ),
      array(
        'title' => 'Телефон',
        'name' => 'phone',
        'required' => true
      ),
      array(
        'title' => 'Ваше имя',
        'name' => 'name',
        'required' => true
      ),
    );
    $rules = DbModelCore::get('userStoreSettings', $this->storeUserId)->r['settings']['rules'];
    if ($rules) {
      $fields[] = array(
        'text' => '<p><b>Правила магазина:</b></p>'.$rules,
        'type' => 'staticText'
      );
    }
    $fields = array_merge($fields, array(
      array(
        'name' => 'col2',
        'type' => 'col',
      ),
      array(
        'title' => 'Почтовый индекс',
        'name' => 'index'
      ),
      array(
        'title' => 'Улица',
        'name' => 'street',
        'help' => 'Пример: ул. Строителей'
      ),
      array(
        'title' => 'Дом',
        'name' => 'dom'
      ),
      array(
        'title' => 'Корпус',
        'name' => 'korpus'
      ),
      array(
        'title' => 'Строение',
        'name' => 'stroenie'
      ),
      array(
        'title' => 'Квартира',
        'name' => 'flat'
      ),
    ));
    parent::__construct($fields);
  }

}