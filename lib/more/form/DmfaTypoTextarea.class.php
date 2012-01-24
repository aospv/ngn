<?php

class DmfaTypoTextarea extends Dmfa {

  public function form2sourceFormat($v) {
    return O::get('FormatText')->cfgSetAutoBrMode(true)->typo($v);
  }
  
  public function source2formFormat($v) {
    return str_replace('<br/>', '', $v);
  }

}
