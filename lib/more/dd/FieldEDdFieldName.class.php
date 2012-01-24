<?php

class FieldEDdFieldName extends FieldEName {

  protected $allowedFormClass = 'DdFormBase';
  
  protected function validate3() {
    if (in_array($this->options['value'], Db::getReservedNames())) {
      $this->error = '"'.$this->options['value'].'" является зарезервированым словом';
      return;
    }
    if (
    $this->valueChanged and
    O::get('DdFields', $this->oForm->strName)->exists($this->options['value'])
    ) {
      $this->error = "Поле с таким именем ({$this->options['value']}) уже существует";
      return;
    }
  }

}