<?php

class FieldEDdStructureName extends FieldEName {

  protected function validate3() {
    if (in_array($this->options['value'], Db::getReservedNames())) {
      $this->error = '"'.$this->options['value'].'" является зарезервированым словом';
      return;
    }
    if (
    $this->valueChanged and
    O::get('DbItems', 'dd_structures')->getItemByField('name', $this->options['value'])
    ) {
      $this->error = "Структура с таким именем ({$this->options['value']}) уже существует";
      return;
    }
  }

}