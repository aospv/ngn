<?php

class PbsSubPages extends PbsSubPagesAbstract {

  static public $title = 'Подразделы определенного раздела';
  
  protected function initFields() {
    $this->fields[] = array(
      'title' => 'Раздел',
      'name' => 'pageId',
      'type' => 'pageId',
      'required' => true
    );
    parent::initFields();
  }

}
