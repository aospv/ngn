<?php

class DdSlaveForm extends DdForm {
  
  public $masterStrName;
  public $masterPageId;
  
  public function __construct(DdFields $oFields, $pageId, $masterStrName, $masterPageId,
  array $options = array()) {
    parent::__construct($oFields, $pageId);
    $this->masterStrName = $masterStrName;
    $this->masterPageId = $masterPageId;
  }
  
}
