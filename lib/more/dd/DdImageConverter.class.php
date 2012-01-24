<?php

class DdImageConverter {
  
  static $smW;
  
  static $smH;
  
  static $mdW;
  
  static $mdH;
  
  /**
   * both/middle/small
   * 
   * @var string
   */
  static $type = 'both';
  
  static function convert($strName, $pageId) {
    if (self::$type == 'small') {
      if (!self::$smW or !self::$smH)
        throw new NgnException('self::$smW or self::$smH not defined');
    } elseif (self::$type == 'middle') {
      if (!self::$mdW or !self::$mdH)
        throw new NgnException('self::$mdW or self::$mdH not defined');
    } else {
      if (!self::$smW or !self::$smH or !self::$mdW or !self::$mdH)
        throw new NgnException('self::$smW or self::$smH or self::$mdW or self::$mdH not defined');
    }  
    $oFields = O::get('DdFields', $strName);
    $oIM = new DdItemsManager(
      new DdItems($pageId),
      new DdForm($oFields, $pageId)
    );
    if (!$imageFields_ = $oFields->getImageFields($strName)) return;
    foreach ($imageFields_ as $k => $v) {
      $imageFields[] = $v['name'];
    }
    $oIM->imageSizes['smW'] = self::$smW;
    $oIM->imageSizes['smH'] = self::$smH;
    $oIM->imageSizes['mdW'] = self::$mdW;
    $oIM->imageSizes['mdH'] = self::$mdH;
    $oIM->getNonActive = true;
    if (!$items = $oIM->oItems->getItems($pageId)) return;
    foreach ($items as $k => $v) {
      foreach ($v as $fieldName => $v2) {
        if (in_array($fieldName, $imageFields)) {
          $imagePath = $oIM->getFilePath($v['id'], $fieldName);
          if (self::$type == 'small') {
            $oIM->makeSmallThumbs(UPLOAD_PATH.'/'.$imagePath);
          } elseif (self::$type == 'middle') {
            $oIM->makeMiddleThumbs(UPLOAD_PATH.'/'.$imagePath);
          } else {
            $oIM->makeThumbs(UPLOAD_PATH.'/'.$imagePath);
          }
        }
      }
    }
  }
  
}