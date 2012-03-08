<?php

class AuthForm extends Form {

  protected function defineOptions() {
    $this->options['id'] = 'frmAuth';
  }
  
  public function __construct(array $options = array()) {
    $fields = array(
      array(
        'name' => 'authLogin',
        'title' => UserRegCore::getAuthLoginTitle(),
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
    parent::__construct(new Fields($fields), $options);
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