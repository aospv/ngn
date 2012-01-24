<?php

class DbModelManager extends DataManagerAbstract {

  protected $modelName;

  public function __construct($modelName, Form $oForm) {
    $this->modelName = $modelName;
    parent::__construct($oForm);
  }
  
  protected function _create() {
    return DbModelCore::create($this->modelName, $this->data);
  }
  
  protected function _update() {
    DbModelCore::update($this->modelName, $this->id, $this->data);
  }
  
  protected function getItem($id) {
    return DbModelCore::get($this->modelName, $id)->r; 
  }
  
  protected function _delete() {
    DbModelCore::delete($this->modelName, $id);
  }
  
  public function updateField($id, $fieldName, $value) {
    DbModelCore::update($this->modelName, $id, array($fieldName => $value));
  }
  
  public function getAttacheFolder() {
    return 'model/'.$this->modelName.'/'.$this->id;
  }

}
