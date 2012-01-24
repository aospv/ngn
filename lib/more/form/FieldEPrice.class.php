<?php

class FieldEPrice extends FieldEFloat {

  protected function prepareValue() {
    parent::prepareValue();
    $this->options['title'] = $this->options['title'].' <span class="gray">(руб.)</span>';
    if (!empty($this->options['value']))
      $this->options['value'] = round($this->options['value'] * 100)/100;
  }
  
}