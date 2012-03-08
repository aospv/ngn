<?php

abstract class PbsDdPage extends PbsPage {

  protected $strName;
  
  protected function initPreFields() {
    parent::initPreFields();
    $this->preFields[0]['dd'] = true;
  }
  
  protected function initRequiredProperties() {
    $strName = DbModelCore::get('pages', $this->preParams['pageId'])->r['strName'];
    if (empty($strName)) throw new EmptyException('$strName');
    $this->strName = $strName;
  }
  
}