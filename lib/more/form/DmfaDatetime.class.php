<?php

class DmfaDatetime extends Dmfa {

  public function form2sourceFormat($v) {
    return $v ? date_reformat($v, 'Y-m-d H:i') : '0000-00-00 00:00:00';
  }
  
  public function source2formFormat($v) {
    if (!$v) return '';
    return preg_replace('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})/', '$3.$2.$1 $4:$5', $v);
  }

}