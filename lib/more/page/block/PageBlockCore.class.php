<?php

class PageBlockCore {
  
  /**
   * @param   string
   * @return  PageBlockStructureAbstract
   */
  static public function getStructure($type, array $options = array()) {
    return O::get('PageBlockStructure_'.$type, $options);
  }
  
  static public function cachable($type) {
    return ClassCore::getStaticProperty('PageBlockView_'.$type, 'cachable');
  }
  
  /**
   * Возвращает массив array(
   *   'blockType' => 'blockTitle',
   *   ...
   * )
   *
   * @return array
   */
  static public function getTypeOptions() {
    return array_merge(array('' => '— выберите —'),
      ClassCore::getStaticProperties('PageBlockStructure_', 'title', 'title'));
  }
  
  static public function getTitle($type) {
    return ClassCore::getStaticProperty('PageBlockStructure_'.$type, 'title');
  }
  
  static public function getDynamicBlockModels($ownPageId) {
    return DbModelCore::collection(
      'pageBlocks',
      DbCond::get()->
        addF('ownPageId', $ownPageId)->
        addF('global', ($ownPageId == 0 ? 1 : 0))->
        setOrder('oid'),
      DbModelCore::modeObject
    );
  }
  
  static protected $blocks = array();
  
  static public function getBlocks($ownPageId, CtrlPage $oController = null) {
    if (isset(self::$blocks[$ownPageId])) return self::$blocks[$ownPageId];
    $oPMSB = new PageModuleStaticBlocks($oController);
    $blocks = $oPMSB->blocks;
    $dynamicBlockModels = self::getDynamicBlockModels($ownPageId);
    $dynamicBlockModels = array_merge($dynamicBlockModels, self::getDynamicBlockModels(0));
    $oPMSB->processDynamicBlockModels($dynamicBlockModels);
    foreach ($dynamicBlockModels as $oPBM)
      $blocks[] = self::getBlockHtmlData($oPBM, $oController);
    return self::$blocks[$ownPageId] = $blocks;
  }
  
  /**
   * Возвращает массив с данными блока и сгенерированным HTML
   *
   * @param   integer   PageID
   * @return  array
   */
  static public function getDynamicBlocks($ownPageId, CtrlPage $oController = null) {
    $blocks = array();
    foreach (self::getDynamicBlockModels($ownPageId) as $oPBM) {
      $blocks[] = self::getBlockHtmlData($oPBM, $oController);
    }
    return $blocks;
  }
  
  static public function getBlockHtmlData(DbModel $oPBM, CtrlPage $oController = null) {
    try {
      $pbv = O::get('PageBlockView_'.$oPBM['type'], $oPBM, $oController);
      $class = ClassCore::nameToClass('Pbvug', $oPBM['type']);
      if (isset($oController) and $oController->userGroup and O::exists($class)) {
        LogWriter::str('dd', $oPBM['id']);
        $block = O::get($class, $pbv)->getData();
      } else {
        $block = $pbv->getData();
      }
    } catch (NgnException $e) {
      $block['colN'] = $oPBM['colN'];
      $block['html'] = $e->getMessage();
    }
    return $block;
  }
  
  static public function getStaticBlockHtmlData($className, $type, CtrlPage $ctrl) {
    return Arr::getValueByKey(
      O::get(ClassCore::nameToClass('Pmsb', $className), $ctrl)->blocks,
      'type', $type
    );
  }
  
  static public function getBlocksByCol($ownPageId, $colN, $oController = null) {
    return Arr::filter_by_value(self::getBlocks($ownPageId, $oController), 'colN', $colN);
  }
  
  static public function getDynamicBlocksCount($ownPageId) {
    return count(self::getDynamicBlocks($ownPageId));
  }
  
  /**
   * Нормализирует номера колонок блоков из имеющихся
   *
   * @param   array     $blocks
   * @param   integer   Всего колонок
   */
  static public function sortBlocks(array $blocks, $colsN) {
    $blocksByCols = array();
    foreach ($blocks as $b) {
      // Если номер колонки блока больше, возможного кол-ва колонок, 
      // помещаем его в последнюю
      if ($b['colN'] > $colsN)
        $b['colN'] = $colsN;
      // Если равен нулю - в первею
      elseif ($b['colN'] == 0)
        $b['colN'] = 1;
      $blocksByCols[$b['colN']][] = $b;
    }
    return $blocksByCols;
  }
  
  static public function cc($id) {
    NgnCache::c()->remove('pageBlock_'.$id);
  }
  
  static public function updateColN($id, $colN) {
    db()->query('UPDATE pageBlocks SET colN=?d WHERE id=?d', $colN, $id);
    self::cc($id);
  }
  
  static public function delete($id) {
    DbModelCore::delete('pageBlocks', $id);
  }
  
  /**
   * @param  array  Array of filter arrays 
   * @return array
   */
  static public function deleteCollection(array $filters = null) {
    $cond = DbCond::get();
    if ($filters) foreach ($filters as $filter) $cond->addF($filter[0], $filter[1]);
    foreach (db()->ids('pageBlocks', $cond) as $id)
      DbModelCore::delete('pageBlocks', $id);
  }
  
  // ------------------- Duplicates -------------------
  
  static public function createGlobalBlocksDuplicates($ownPageId) {
    if (PageLayoutN::get(0) != PageLayoutN::get($ownPageId))
      throw new NgnException(
        "Global and pageId=$ownPageId page layouts must be equals");
    foreach (self::getDynamicBlocks(0) as $v) {
      DbModelCore::create('pageBlocks', array(
        'ownPageId' => $ownPageId,
        'colN' => $v['colN'],
        'type' => 'duplicate',
        'global' => 0,
        'settings' => array('duplicateBlockId' => $v['id'])
      ));
    }
    Settings::set('globalBocksAdded'.$ownPageId, true);
  }
  
  static public function deleteDuplicateBlocks($ownPageId) {
    foreach (Arr::filter_by_value(
    self::getDynamicBlocks($ownPageId), 'type', 'duplicate') as $v) {
      self::delete($v['id']);
    }
    Settings::delete('globalBocksAdded'.$ownPageId, true);
  }
  
  static public function globalBlocksDuplicatesExists($ownPageId) {
    return Settings::get('globalBocksAdded'.$ownPageId);
  }

}