<?php

class ItemsManager extends DataManagerAbstract {
  
  /**
   * @var UpdatableItems
   */
  public $oItems;
  
  public function __construct(UpdatableItems $oItems, Form $oForm, array $options = array()) {
    $this->oItems = $oItems;
    parent::__construct($oForm, $options);
  }
  
  protected function getItem($id) {
    return $this->oItems->getItem($id);
  }
  
  protected function _create() {
    return $this->oItems->create($this->data);
  }
  
  protected function afterCreate() {
    //$this->oItems->event('createItem', $this->id);
  }
  
  protected function _update() {
    $this->oItems->update($this->id, $this->data);
  }
  
  protected function afterUpdate() {
    //$this->oItems->event('updateItem', $this->id);
  }
  
  protected function _delete() {
    $this->oItems->delete($this->id);
    //$this->oItems->event('deleteItem', $this->id);
  }
  
  public function updateField($id, $fieldName, $value) {
    if (BracketName::getKeys($fieldName) !== false) {
      $data = $this->getItem($id);
      BracketName::setValue($data, $fieldName, $value);
      $this->oItems->update($id, $data);
    } else {
      $this->oItems->updateField($id, $fieldName, $value);
    }
  }

}
