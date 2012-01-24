<?php

class Notify_SendMethodForm extends Form {
  
  public function __construct() {
    /* @var $oNotify_Sender Notify_Sender */
    $oNotify_Sender = O::get('Notify_Sender');
    $titles = array(
      'privMsgs' => LANG_MESSAGES,
      'email' => LANG_EMAIL
    );
    foreach ($oNotify_Sender->getSendMethods() as $method)
      $options[$method] = $titles[$method];
      
    parent::__construct(
      new Fields(array(
        array(
          'title' => 'Методы отправки уведомлений',
          'name' => 'sendMethods',
          'type' => 'multiselect',
          'options' => $options,
          'required' => true
        )
      ))
    );
    $this->setElementsData(array(
      'sendMethods' => UsersSettings::get(Auth::get('id'), 'sendMethods')
    ));
  }
  
  protected function _update(array $data) {
    UsersSettings::set(Auth::get('id'), array('sendMethods'), $data['sendMethods']);
  }
  
}
