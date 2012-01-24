<?php

class FieldEFloat extends FieldEText {

  protected function prepareValue() {
    if (!empty($this->options['value']))
      $this->options['value'] = floatval(str_replace(',', '.', $this->options['value']));
  }

}