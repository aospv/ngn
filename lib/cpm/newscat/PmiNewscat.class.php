<?php

class PmiNewscat extends PmiNews {
  
  public $title = 'Новости по категориям';
  public $oid = 30;

  public function __construct(array $options = array()) {
    $this->ddFields[] = array(
      'title' => 'Рубрика',
      'name' => 'rubric',
      'type' => 'tagsSelect'
    );
    parent::__construct($options);
  }

}
