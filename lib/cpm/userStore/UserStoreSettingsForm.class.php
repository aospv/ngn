<?php

class DbModelUserSettings extends DbModel {

  static public $serializeble = array(
    'settings'
  );
  
  static public $hasAutoIncrement = false;

}

class DbModelUserStoreSettings extends DbModelUserSettings {
}

class UserStoreSettingsForm extends Form {

  protected $userId;

  public function __construct($userId) {
    $this->userId = $userId;
    parent::__construct(new Fields(array(
      array(
        'name' => 'col1',
        'type' => 'col'
      ),
      array(
        'name' => 'deliveryWays',
        'title' => 'Способы доставки',
        'type' => 'multiselect',
        'minNum' => 1,
        //'rowClass' => 'newLine',
        'options' => StoreCore::getDeliveryWays()
      ),
      array(
        'name' => 'paymentWays',
        'title' => 'Способы доставки',
        'type' => 'multiselect',
        'minNum' => 1,
        //'rowClass' => 'newLine',
        'options' => StoreCore::getPaymentWays()
      ),
      array(
        'name' => 'col2',
        'type' => 'col'
      ),
      array(
        'name' => 'rules',
        'title' => 'Правила вашего магазина',
        'type' => 'wisiwigSimple'
      ),
      array(
        'title' => 'Условия возврата и обмена',
        'name' => 'returnRules',
        'type' => 'typoTextarea'
      )
    )));
    if (($settings = DbModelCore::get('userStoreSettings', $userId)) !== false)
      $this->setElementsData($settings['settings']);
  }
  
  protected function _update(array $data) {
    DbModelCore::replace('userStoreSettings', $this->userId, array('settings' => $data));
    if (DbModelCore::$replaceCreate) SFLM::clearJsCssCache();
  }

}