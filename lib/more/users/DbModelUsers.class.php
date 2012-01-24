<?php

class DbModelUsers extends DbModel {

  public function getClean() {
    return Arr::filterExceptKeys($this->r, array('pass', 'passClear'));
  }
  
  public function checkPass($pass) {
    return Auth::cryptPass($pass) == $this->r['pass'];
  }
  
  static public function beforeCreateUpdate(array &$data) {
    if (!empty($data['pass'])) {
      $data['passClear'] = $data['pass'];
      $data['pass'] = Auth::cryptPass($data['pass']);
    }
  }
  
  static public function searchUser($mask) {
    $mask = $mask.'%';
    return db()->selectCol("
      SELECT id AS ARRAY_KEY, login FROM users WHERE
      active=1 AND id>0 AND login LIKE ? LIMIT 10", 
      $mask);
  }
  

}