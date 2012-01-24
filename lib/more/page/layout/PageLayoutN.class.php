<?php

class PageLayoutN {
  
  static protected $default = 4;

  static public function get($pageId = 0) {
    $r = Settings::get('pageLayout_'.$pageId, true);
    if (!$pageId or !$r) {
      // Если не существует настроек для раздела, получаем настройки по умолчанию
      if (!($r = Settings::get('pageLayout_0'))) {
        // Если нет настроек по умолчанию, получаем из свойств полей
        return self::$default;
      }
    }
    return $r;
  }
  
  static public function delete($pageId) {
    Settings::delete('pageLayout_'.$pageId);
    self::deleteBlocks($pageId);
  }
  
  static public function save($pageId, $layoutN) {
    self::moveBlocks($pageId, $layoutN);
    Settings::set('pageLayout_'.$pageId, $layoutN);
  }
  
  static public function getItems() {
    return Settings::getItems('pageLayout_');
  }
  
  static protected function moveBlocks($pageId, $layoutN) {
    $curLayoutN = 2;
    $layouts = PageLayout::getLayouts();
    foreach ($layouts[$layoutN]['cols'] as $colN => $col) {
      if ($col['allowBlocks']) {
        $goodColN = $colN;
        break;
      }
    }
    if (!isset($goodColN)) {
      //PageBlockCore::deleteByPageId($pageId);
      return;
    }
    foreach (PageBlockCore::getDynamicBlocks($pageId) as $v) {
      if (empty($layouts[$layoutN]['cols'][$v['colN']]['allowBlocks']))
        PageBlockCore::updateColN($v['id'], $goodColN);
    }
  }
  
  static protected function deleteBlocks($pageId) {
    if ($pageId)
      PageBlockCore::deleteCollection(array(array('ownPageId', $pageId)));
    else {
      throw new NgnException('not realized');
      PageBlockCore::deleteCollection(array(array('global', 1)));
    }
    NgnCache::clean();
  }
  
}
