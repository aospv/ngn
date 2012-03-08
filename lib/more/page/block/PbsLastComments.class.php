<?php

class PbsLastComments extends PbsAbstract {

  static public $title = 'Последние комментарии';
  
  protected function initFields() {
    $this->fields[] = array(
      'title' => 'Лимит',
      'name' => 'limit',
      'type' => 'num',
    );
  }

}