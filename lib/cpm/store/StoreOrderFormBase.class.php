<?php

class StoreOrderFormBase extends Form {

  public function __construct(array $cartItems, array $options = array()) {
    $this->cartItems = $cartItems;
    $this->im = DdCore::getItemsManager(Config::getVarVar('store', 'ordersPageId'));
    $this->im->oItems->forceDublicateInsertCheck = true;
    parent::__construct(
      new Fields(),
      array_merge(array('submitTitle' => 'Отправить заказ'), $options)
    );
  }
  
  protected function defineOptions() {
    $this->options['cartProductsTitle'] = 'Вы покупаете следующите товары';
    $this->options['jsOrderListOptions'] = array();
  }
  
  protected $data = array();
  
  protected function init() {
    if (Auth::get('id')) {
      $this->data['fullName'] = Auth::get('login');
      if (Auth::get('phone')) $this->data['phone'] = Auth::get('phone');
      $this->oFields->fields = array_merge(
        $this->im->oForm->oFields->fields,
        $this->getAuthorizedFields()
      );
    } else {
      $this->oFields->fields = $this->im->oForm->oFields->fields;
    }
    $this->oFields->fields = array_merge(
      array($this->getCartProductsField()),
      $this->oFields->fields
    );
  }
  
  protected function getAuthorizedFields() {
    $r = array(
      'fullName' => array(
        'name' => 'fullName',
        'type' => 'staticTitledText',
        'title' => 'Ваше имя',
        'text' => Auth::get('login')
      )
    );
    if (($phone = Auth::get('phone'))) {
      $r['phone'] = array(
        'name' => 'phone',
        'type' => 'staticTitledText',
        'title' => 'Телефон',
        'text' => $phone
      );
    }
    return $r;
  }
  
  protected function getCartProductsField() {
    return array(
      'name' => 'orderItems',
      'noValue' => true,
      'text' => '<p><b>'.$this->options['cartProductsTitle'].':</b></p>'.
        Tt::getTpl('pageModules/storeOrder/cartProducts', $this->cartItems),
      'type' => 'staticText',
    );
  }
  
  protected function _update(array $data) {
    $id = $this->im->create(array_merge($data, $this->data));
    //StoreCart::get()->clear();
    Ngn::fireEvent('store.newOrder', array($id, $this->cartItems));
  }

}
