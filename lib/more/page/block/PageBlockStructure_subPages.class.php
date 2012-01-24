<?php

class PageBlockStructure_subPages extends PageBlockStructure_subPagesAbstract {

  static public $title = 'Подразделы определенного раздела';
  
  protected function initFields() {
    $this->fields[] = array(
      'title' => 'Раздел',
      'name' => 'pageId',
      'type' => 'page',
      'required' => true
    );
    parent::initFields();
  }

}