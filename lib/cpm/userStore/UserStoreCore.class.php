<?php

class UserStoreCore {

  static public function allowed($userId) {
    return in_array(
      DbModelCore::get('users', $userId)->r['role'],
      Config::getVarVar('userStore', 'roles')
    );
  }
  
  static public function getDeliveryWays($userId) {
    $settings = DbModelCore::get('userStoreSettings', $userId)->r['settings'];
    return Arr::filter_by_keys(StoreCore::getDeliveryWays(), $settings['deliveryWays']);
  }
  
  static public function getPaymentWays($userId) {
    $settings = DbModelCore::get('userStoreSettings', $userId)->r['settings'];
    return Arr::filter_by_keys(StoreCore::getPaymentWays(), $settings['paymentWays']);
  }

}