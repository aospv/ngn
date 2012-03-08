<?php

class StoreCore {
  
  static public function getDeliveryWays() {
    return array(
      'post' => 'Почта',
      'exspressDelivery' => 'Экспресс-доставка на дом',
      'transportCompany' => 'Транспортная компания',
      'self' => 'Самовывоз / Личная встреча',
      'trainConductor' => 'Проводник поезда',
      'courier' => 'Курьер'
    );
  }
  
  static public function getPaymentWays() {
    return array(
      'cache' => 'Наличные',
      'bankTransfer' => 'Банковский перевод на счёт',
      'postTransfer' => 'Почтовый перевод',
      'cardTransfer' => 'Перевод на банковскую карту',
      'epay' => 'Электронные платёжные системы',
      'transferSystem' => 'Система денежных переводов',
      'mobile' => 'Пополнение счёта мобильного телефона'
    );
  }
  
  static public function getOrderController() {
    return 'storeOrder'.ucfirst(Config::getVarVar('store', 'orderControllerSuffix'));
  }

}