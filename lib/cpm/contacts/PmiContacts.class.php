<?php

class PmiContacts extends PmiDd {
  
  public $title = 'Контакты';
  public $oid = 50;
  
  protected $behaviorNames = array(
    'feedback',
    'sliceAddress'
  );
  
  protected $ddFields = array(
    array(
      'title' => 'Ваше имя',
      'name' => 'name',
      'type' => 'typoText',
      'required' => true
    ),
    array(
      'title' => 'E-mail для обратной связи',
      'name' => 'email',
      'type' => 'email',
      'required' => true
    ),
    array(
      'title' => 'Сообщение',
      'name' => 'text',
      'type' => 'typoTextarea',
      'required' => true
    )
  );

  protected function getSettings() {
    return array_merge(
      parent::getSettings(),
      array(
        'completeRedirectType' => 'complete',
        'showFormOnDefault' => true, 
        'doNotShowItems' => true,
        'createBtnTitle' => 'Отправить',
        'itemTitle' => 'сообщение',
      )      
    );
  }

}
