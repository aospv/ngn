<?php

class CtrlAdminPhotoalbumSlaveImport extends CtrlAdminPhotoImport {

  protected function getItemParams() {
    return array(
      DdCore::masterFieldName => $this->getParam(3)
    );
  }

}