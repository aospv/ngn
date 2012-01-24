<?php

/**
 * Без поддержки вложенных файлов и изображений
 */
class DmfaWisiwigSimple extends Dmfa {

  public function form2sourceFormat($v) {
    return $oFormatText = O::get('FormatText', array(
      'allowedTagsConfigName' => 'tiny.simple.allowedTags'
    ))->html($v);
  }
  
}