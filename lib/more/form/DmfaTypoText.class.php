<?php

class DmfaTypoText extends Dmfa {

  public function form2sourceFormat($v) {
    return O::get('FormatText')->typo($v);
  }

}
