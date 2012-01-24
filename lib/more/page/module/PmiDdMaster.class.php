<?php

abstract class PmiDdMaster extends PmiDd {
  
  public $controller = 'ddItemsMaster';
  protected $masterStrName;
  protected $masterTitle;
  protected $masterFields;
  protected $slaveController = 'ddItems';
  protected $slaveTitle;
  protected $slavePageName;
  protected $slaveFields;
  private $slaveModule;
  private $slaveStrName;
  protected $requiredProperties = array(
    'masterTitle',
    'masterFields',
    'slavePageName',
    'slaveFields',
  );
  
  public function __construct() {
    parent::__construct();
    $this->slaveModule = $this->module.'Slave';
    if (!isset($this->masterTitle)) $this->masterTitle = $this->title;
    if (!isset($this->masterStrName)) $this->masterStrName = $this->strName;
    $this->slaveStrName = DdCore::getSlaveStrName($this->masterStrName);
  }
  
  protected function getSlaveTitle() {
    return $this->masterTitle.': '.$this->slaveTitle;
  }
  
  protected function afterCreate($node) {
    $this->createSlavePage($this->pageId);
  }
  
  protected function createStructure() {
    $this->createMasterStructure();
    $this->createSlaveStructure();
  }
  
  protected function createMasterStructure() {
    if ($this->oSM->oItems->getItemByField('name', $this->masterStrName)) return;
    $this->oSM->create(array(
      'title' => $this->masterTitle,
      'name' => $this->masterStrName
    ));
    $oDFM = new DdFieldsManager($this->masterStrName);
    foreach ($this->masterFields as $field) {
      $oDFM->create($field);
    }
  }
  
  protected function createSlaveStructure() {
    if ($this->oSM->oItems->getItemByField('name', $this->slaveStrName)) return;
    $this->oSM->create(array(
      'title' => $this->getSlaveTitle(),
      'name' => $this->slaveStrName
    ));
    $this->createSlaveFields();
  }
  
  protected function createSlaveFields() {
    $oDdFields = new DdFieldsManager($this->slaveStrName);
    $oDdFields->create(array(
      'name' => DdCore::masterFieldName,
      'title' => $this->masterTitle,
      'type' => 'ddItemsSelect',
      'required' => true
    ));
    foreach ($this->slaveFields as $field)
      $oDdFields->create($field);
  }
  
  protected function createSlavePage($masterPageId) {
    $pageData = array(
      'title' => $this->getSlaveTitle(),
      'name' => $this->slavePageName,
      'folder' => 0,
      'active' => 1,
      'onMenu' => 0,
      'onMap' => 0,
      'parentId' => $masterPageId,
      'oid' => 0,
      'controller' => $this->slaveController,
      'module' => $this->slaveModule,
      'slave' => 1,
    );
    $settings = array(
      'strName' => $this->slaveStrName,
      'masterStrName' => $this->masterStrName,
      'masterPageId' => $masterPageId
    );
    $settings += $this->getSlaveSettings();
    $pageData['settings'] = $settings;
    $slavePageId = DbModelCore::create('pages', $pageData);
    DbModelPages::addSettings($masterPageId, array(
      'slavePageId' => $slavePageId,
    ));
  }
  
  protected function getSettings() {
    return array(
      'strName' => $this->masterStrName
    );
  }

  protected function getSlaveSettings() {
    return array();
  }

}