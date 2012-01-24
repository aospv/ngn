<?php

class FieldEDatetime extends FieldEDate {

  protected function defineOptions() {
    $this->options['maxlength'] = 19;
    $this->options['help'] = 'Формат даты: ДД.ММ.ГГГГ ЧЧ:ММ';
    //$this->options['cssClass'] = "validate-date dateFormat:'%d.%m.%Y %H:%M'";
  }

  protected function init() {
    parent::init();
    if (preg_match('/(\d+)\D+(\d+)\D+(\d+)\D+(\d+)\D+(\d+)/', $this->options['value'], $this->m)) {
      $this->options['value'] = sprintf("%02s.%02s.%04s %02s:%02s",
        $this->m[1], $this->m[2], $this->m[3], $this->m[4], $this->m[5]);
    }
  }
  
  static public function form2sourceFormat($v) {
    return $v ? date_reformat($v, 'Y-m-d') : '0000-00-00';
  }
  
  static public function source2formFormat($v) {
    if (!$v) return '';
    preg_match('/(\d+)-(\d+)-(\d+) (\d+):(\d+):(\d+)/', $v, $m);
    return $m[3].'.'.$m[2].'.'.$m[1].' '.$m[4].':'.$m[5];
  }
  
  public function _js() {
    return "
    $('{$this->oForm->id}').getElements('.type_{$this->type}').each(function(el) {
      new Ngn.DatePicker(el.getElement('input'), {
        pickerClass: 'datepicker_cp',
        timePicker: true
      });
    });
    ";
  }
  
}