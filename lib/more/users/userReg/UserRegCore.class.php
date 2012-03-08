<?php

class UserRegCore {

  static public function getLoginTitle() {
    if (Config::getVarVar('userReg', 'loginAsFullName'))
      return 'Ф.И.О.';
    else
      return 'Логин';
  }
  
  static public function getAuthLoginTitle() {
    $loginTitle = Config::getVarVar('userReg', 'loginAsFullName') ? 'Ф.И.О.' : 'Логин';
    if (Config::getVarVar('userReg', 'emailEnable')) $loginTitle .= ' / E-mail';
    if (Config::getVarVar('userReg', 'phoneEnable')) $loginTitle .= ' / Телефон';
    return $loginTitle;
  }
  
  static public function getLoginField() {
  	if (Config::getVarVar('userReg', 'loginAsFullName')) {
      return array(
  	    'name' => 'login',
  	    'title' => 'Ф.И.О.',
  	    'type' => 'regLogin',
  	    'validator' => 'fullName',
  	    'required' => true
  	  );
  	} else {
  	  return array(
  	    'name' => 'login',
  	    'title' => 'Логин',
        'type' => 'regLogin',
        'required' => true
  	  );
    }
  }
  
}
