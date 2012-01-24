<?php
  
class DdImportField extends DdFields {
  
  public function __construct($strName) {
    parent::__construct($strName);
    $this->getSystem = false;
  }

  public function getFields() {
    $fields = array();
    foreach (parent::getFields() as $k => $v) {
      if (!$this->isFileType($v['type']))
        $fields[$k] = $v;
    }
    return $fields;
  }

}