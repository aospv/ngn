<?php

class FieldERegLogin extends FieldETypoText {

  protected function validate2() {
    if ($this->valueChanged and DbModelCore::get('users', $this->options['value'], 'login')) {
      $this->error = 'Пользователь с таким логином уже существует';
    }
  }
  
}
