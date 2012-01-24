<?php

class FieldETextareaTypo extends FieldETextarea {

  public function beforeCreateUpdate(DdItemsManager $oDM) {
    $oFormatText = O::get('FormatText', array(
      'allowedTagsConfigName' => 'comments.allowedTags'
    ));
    $oFormatText->oJevix->cfgSetAutoBrMode(true);
    $oDM->data[$this->options['name'].'_f'] = $oFormatText->html($this->options['value']);
  }

}
