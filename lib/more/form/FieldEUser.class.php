<?php

/*
class FieldEUser extends FieldEAutocompleter {

  protected function validate2() {
    if (!DbModelCore::get('users', $this->options['value'])) {
      $this->error = "Пользователя с ID={$this->options['value']} не существует";
    }
  }
  
  public function _html() {
    $login = !empty($this->options['value']) ?
      DbModelCore::get('users', $this->options['value'])->r['login'] : null;
    return $this->__html($login);
  }

}
*/

class FieldEUser extends FieldENum {
}