<?php

class PmiDdDummy extends PmiDd {

  public $title = 'Пустышка';
  public $oid = 500;
	
  protected $ddFields = array(
    array(
      'title' => 'Заголовок',
      'name' => 'title',
      'type' => 'text'
    )
  );
  
}