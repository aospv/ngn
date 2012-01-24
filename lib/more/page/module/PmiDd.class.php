<?php

abstract class PmiDd extends Pmi {
  
  /**
   * @var DdStructuresManager
   */
  protected $oSM;

  public $controller = 'ddItems';
  public $hasTopSlice = true;
  public $hasBottomSlice = false;
  protected $strName;
  protected $ddFields;
  protected $requiredProperties = array(
    'ddFields',
  );
  
  public function __construct(array $options = array()) {
    parent::__construct($options);
    $this->strName = $this->module;
    $this->oSM = new DdStructuresManager();
  }
  
  protected $strType = 'dynamic';
  protected function createStructure() {
    if ($this->oSM->oItems->getItemByField('name', $this->strName)) return;
    if (!$this->oSM->create(array(
      'title' => $this->title,
      'name' => $this->strName,
      'type' => $this->strType
    ))) {
      throw new NgnException($this->oSM->oForm->getLastError());
    }
    $oFM = new DdFieldsManager($this->strName);
    foreach ($this->ddFields as $field) {
      if (!$oFM->create($field))
        throw new NgnException($oFM->oForm->getLastError());
    }
  }
  
  public function install($node) {
    $this->createStructure();
    $node['strName'] = $this->strName;
    parent::install($node);
    $this->createSlices($this->pageId, $node['title']);
    $this->updatePageLayout();
    return $this->pageId;
  }
  
  protected function getSettings() {
    return array(
      'strName' => $this->strName
    );
  }
  
  protected function createSlices($pageId, $pageTitle) {
    if (!ClassCore::hasAncestor('Ctrl'.$this->controller, 'CtrlDdItems')) return;
    if ($this->hasTopSlice) {
      Slice::replace(array(
        'id' => 'beforeDdItems_'.$pageId,
        'pageId' => $pageId,
        'title' => 'Блок над записями: '.$pageTitle,
        'text' => ''
      ));
    }
    if ($this->hasBottomSlice) {
      Slice::replace(array(
        'id' => 'afterDdItems_'.$pageId,
        'pageId' => $pageId,
        'title' => 'Блок под записями: '.$pageTitle,
        'text' => ''
      ));
    }
  }
  
  protected $pageLayout = false;
  
  protected function updatePageLayout() {
    if ($this->pageLayout === false) return;
    PageLayoutN::save($this->pageId, $this->pageLayout);
  }
  
} 
