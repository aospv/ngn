<?php

class FieldEPage extends FieldEAutocompleter {

  protected function validate2() {
    if (!DbModelCore::get('pages', $this->options['value'])) {
      $this->error = "Пользователя с ID={$this->options['value']} не существует";
    }
  }
  
  public function _html() {
    $login = !empty($this->options['value']) ?
      DbModelCore::get('pages', $this->options['value'])->r['title'] : null;
    return $this->__html($login);
  }

}