<?php

class PageBlockStructure_slaveSubItems extends PageBlockStructure_items {

  static public $title = 'Slave-записи текущей master записи';
  
  protected function initPreFields() {
  }
  
  public function getHiddenParams() {
    Arr::checkEmpty($this->options, 'pageId');
    return array('pageId' => $this->options['pageId']);
  }
  
}