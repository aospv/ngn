<?php

class CtrlCommonPhotoalbumSlaveImport extends CtrlCommonPhotoImport {

  public function getItemParams() {
    return array(
      DdCore::masterFieldName => $this->getParam(3)
    );
  }

}