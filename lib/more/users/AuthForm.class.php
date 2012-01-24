<?php

class AuthForm extends Form {

  public function __construct($submitTitle = 'Войти') {
    $fields = array(
      array(
        'name' => 'authLogin',
        'title' => 'Логин',
        'type' => 'text',
        'required' => true
      ),
      array(
        'name' => 'authPass',
        'title' => 'Пароль',
        'type' => 'password',
        'required' => true
      )
    );
    parent::__construct(new Fields($fields));
    $this->options['submitTitle'] = $submitTitle;
  }
  
  public function isSubmittedAndValid() {
    if (!parent::isSubmittedAndValid()) return false;
    $data = $this->getData();
    if (!Auth::loginByPost($data['authLogin'], $data['authPass'])) {
      if (in_array(Auth::$errors[0]['code'], array(
        Auth::ERROR_AUTH_NO_LOGIN,
        Auth::ERROR_AUTH_USER_NOT_ACTIVE,
        Auth::ERROR_EMPTY_LOGIN_OR_PASS
      ))) $this->getElement('authLogin')->error(Auth::$errors[0]['text']);
      else $this->getElement('authPass')->error(Auth::$errors[0]['text']);
      return false; 
    }
    return true;
  }

}