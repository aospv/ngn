<?php

DdFieldCore::registerType('ddItemsSelect', array(
  'dbType' => 'INT',
  'dbLength' => 11,
  'title' => 'Выбор dd-записи',
  'order' => 300
));

class FieldEDdItemsSelect extends FieldESelect {

  protected $allowedFormClass = 'DdSlaveForm';
  
  protected function init() {
    $this->options['options'] = array('' => '—');
    $oI = new DdItems($this->oForm->masterPageId);
    foreach ($oI->getItems() as $id => $v)
      $this->options['options'][$id] = $v['title'];
    parent::init();
  }

}