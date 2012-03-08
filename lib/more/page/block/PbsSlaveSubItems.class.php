<?php

class PbsSlaveSubItems extends PbsItems {

  static public $title = 'Slave-записи текущей master записи';
  
  protected function initPreFields() {
  }
  
  public function getHiddenParams() {
    Arr::checkEmpty($this->options, 'pageId');
    return array('pageId' => $this->options['pageId']);
  }
  
}