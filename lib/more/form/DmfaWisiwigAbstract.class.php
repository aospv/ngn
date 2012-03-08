<?php

class DmfaWisiwigAbstract extends Dmfa {
  
  public function form2sourceFormat($v) {
    if (!$this->oDM->typo) return $v;
    if (!Config::getVar('tiny', 'typo')) return $v;
    return $oFormatText = O::get('FormatText', array(
      'allowedTagsConfigName' => 'tiny.admin.allowedTags'
    ))->html($v);
  }
  
  /*
  public function source2formFormat($v) {
    return htmlspecialchars($v);
  }
  */

}