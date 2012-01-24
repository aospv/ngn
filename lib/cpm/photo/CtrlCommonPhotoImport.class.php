<?php

class CtrlCommonPhotoImport extends CtrlCommon {

  public $defaultAction = 'json_upload';
  public $paramActionN = 4;

  protected function addSubControllers() {
    $this->addSubController(new SubPaPhotoImport($this, $this->getParam(2)));
  }
  
  public function getItemParams() {
    return array();
  }

}