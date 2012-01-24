<?php

class PageBlockModelManager extends DbModelManager {

  protected $createParams;

  public function __construct(PageBlockStructureAbstract $oPBS, array $createParams = array()) {
    parent::__construct('pageBlocks', new Form(new Fields($oPBS->getFields())));
    $this->createParams = $createParams;
  }

  public function updateField($id, $fieldName, $value) {
    $model = DbModelCore::get($this->modelName, $id);
    BracketName::setValue($model->r['settings'], $fieldName, $value);
    $model->save();
  }
  
  protected function _create() {
    $d = $this->createParams;
    $d['settings'] = $this->data;
    // Костыль для блоков структуры PageBlockStructurePage
    if (!empty($d['settings']['pageId'])) $d['pageId'] = $d['settings']['pageId'];
    $this->data = $d;
    return parent::_create();
  }
  
  protected function _update() {
    $this->data = array('settings' => array_merge(
      DbModelCore::get($this->modelName, $this->id)->r['settings'],
      $this->data
    ));
    parent::_update();
  }
  
  protected function getItem($id) {
    return DbModelCore::get($this->modelName, $id)->r['settings'];
  }

}
