<?php

class PcpItemsMaster extends PcpDdItems {

  public $title = 'Записи (master)';

  public function getProperties() {
    return Arr::append(parent::getProperties(), array(
      array(
        'name' => 'slavePageId', 
        'title' => 'Slave-раздел', 
        'type' => 'num', 
        'maxlength' => 50,
        'required' => true
      )
    ));
  }

}