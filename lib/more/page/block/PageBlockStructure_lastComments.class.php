<?php

class PageBlockStructure_lastComments extends PageBlockStructureAbstract {

  static public $title = 'Последние комментарии';
  
  protected function initFields() {
    $this->fields[] = array(
      'title' => 'Лимит',
      'name' => 'limit',
      'type' => 'num',
    );
  }

}