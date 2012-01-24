<?php

class PageMetatagsFields extends Fields {
  
  public function __construct() {
    parent::__construct(array(
      array(
        'name' => 'title',
        'title' => 'Title',
        'type' => 'text',
      ),
      array(
        'name' => 'description',
        'title' => 'Текст',
        'type' => 'textarea'
      ),
      array(
        'name' => 'keywrods',
        'title' => 'Текст',
        'type' => 'textarea'
      ),
    ));
  }
  
}
