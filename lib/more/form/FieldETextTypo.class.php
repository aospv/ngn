<?php

class FieldETextTypo extends FieldEText {

  public function beforeCU_text(DdItemsManager $oDM) {
    /* @var $oFormatText FormatText */
    $oFormatText = O::get('FormatText');
    $oDM->data[$this->options['name'].'_f'] = $oFormatText->typo($this->options['value']);
  }

}
