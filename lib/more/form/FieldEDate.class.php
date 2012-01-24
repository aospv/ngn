<?php

class FieldEDate extends FieldEText {

  protected function defineOptions() {
    $this->options['maxlength'] = 10;
    $this->options['help'] = 'Формат даты: ДД.ММ.ГГГГ';
    $this->options['cssClass'] = "validate-date dateFormat:'%d.%m.%Y'";
  }
  
  protected $m;
  
  protected function init() {
    parent::init();
    if (preg_match('/(\d+)\D+(\d+)\D+(\d+)/', $this->options['value'], $this->m)) {
      $this->options['value'] = sprintf("%02s.%02s.%04s",
        $this->m[1],  $this->m[2],  $this->m[3]);
    }
  }
  
  protected function validate2() {
    if (empty($this->m)) {
      $this->error = 'Формат даты неправильный';
    } else {
      if ($this->m[3] <= 38) $this->m[1] += 2000;
      elseif ($this->m[3] <= 99) $this->m[1] += 1900;
      if (!checkdate($this->m[2], $this->m[1], $this->m[3])) {
        $this->error = 'Дата выходит за пределы разумного';
      }
    }
  }

}