<?php

class FieldEPhone extends FieldEText {

  protected function defineOptions() {
    $this->options['cssClass'] = 'validate-phone';
    $this->options['help'] = 'Пример: +79202123933';
  }
  
  protected function prepareValue() {
    if (empty($this->options['value'])) return;
    $this->options['value'] = trim($this->options['value'], '+ ');
  }
  
  protected function prepareInputValue($value) {
    return '+'.$value;
  }

}