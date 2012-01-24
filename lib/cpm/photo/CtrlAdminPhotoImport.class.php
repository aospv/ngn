<?php

class CtrlAdminPhotoImport extends CtrlAdmin {

  protected function init() {
    parent::init();
    $this->addSubController(new SubPaPhotoImport($this, $this->getParam(2)));
  }

}
