<?php

class DdSlaveItems extends DdItems {
  
  private $masterStrName;
  
  private $masterPageId;
  
  public function __construct($pageId, $masterStrName, $masterPageId) {
    parent::__construct($pageId);
    $this->masterStrName = $masterStrName;
    $this->masterPageId = $masterPageId;
    if (!isset($this->masterStrName))
      throw new NgnException('$this->masterStrName not defined');
    if (!isset($this->masterPageId))
      throw new NgnException('$this->masterPageId not defined');
  }
  
  public function create(array $data) {
    $id = parent::create($data);
    $this->clearMasterCache($id);
    return $id;
  }
  
  public function update($id, array $data) {
    // необходимо в случае переноса записи в другой master-раздел
    $this->clearMasterCache($id);
    parent::update($id, $data);
    $this->clearMasterCache($id);
  }
  
  public function activate($id) {
    parent::activate($id);
    $this->clearMasterCache($id);
  }
  
  public function deactivate($id) {
    parent::deactivate($id);
    $this->clearMasterCache($id);
  }
  
  public function delete($id) {
    $this->clearMasterCache($id);
    parent::delete($id);
  }
  
  protected function clearMasterCache($id) {
    $item = $this->getItem($id);
    $this->_clearMasterCache($item[DdCore::masterFieldName]);
  }

  protected function _clearMasterCache($masterItemId) {
    DdItemsCacher::cc($this->masterStrName, $masterItemId);
    PageModuleCore::action($this->page['module'], 'clearMasterCache', array(
      'masterStrName' => $this->masterStrName,
      'masterItemId' => $masterItemId
    ));
  }
  
  protected function extendItem(array &$item) {
    $item[DdCore::masterFieldName] = O::get('DdItems', $this->masterPageId)->
      getItem($item[DdCore::masterFieldName]);
  }

  protected function extendItems(array &$items) {
    foreach ($items as &$item)
      $this->extendItem($item);
  }
  
}