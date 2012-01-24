<?php

class FieldEEmail extends FieldEText {

  protected function defineOptions() {
    $this->options['cssClass'] = 'validate-email';
  }
  
  protected function validate2() {
    if (!preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $this->options['value']))
      $this->error = "Неправильный формат e-mail'a";
  }

}
