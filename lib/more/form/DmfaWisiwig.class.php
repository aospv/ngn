<?php

class DmfaWisiwig extends DmfaWisiwigAbstract {

  public function form2sourceFormat($v) {
    if (!$this->oDM->typo) return $v;
    if (!Config::getVar('tiny', 'typo')) return $v;
    return $oFormatText = O::get('FormatText', array(
      'allowedTagsConfigName' => 'tiny.admin.allowedTags'
    ))->html($v);
  }
  
  public function afterCreate(FieldEWisiwig $el) {
    $this->oDM->moveTempFiles($el->options['value'], $this->oDM->id, $el->options['name']);
    $this->oDM->cleanupImages($el->options['value'], $this->oDM->id, $el->options['name']);
    $this->oDM->updateField($this->oDM->id, $el->options['name'], $el->options['value']);
  }
  
  public function afterUpdate(FieldEWisiwig $el) {
    $value = BracketName::getValue($this->oDM->data, $el->options['name']);
    $this->oDM->cleanupImages($value, $this->oDM->id, $el->options['name']);
    $this->oDM->updateField($this->oDM->id, $el->options['name'], $value);
  }

}
