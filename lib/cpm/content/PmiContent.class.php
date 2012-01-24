<?php

class PmiContent extends PmiDd {
  
  public $title = 'Контент';
  public $oid = 10;
  public $controller = 'ddItem';
  public $hasTopSlice = false;
  
  protected $ddFields = array(
    array(
      'title' => 'Текст',
      'name' => 'text',
      'type' => 'wisiwig'
    )
  );
  protected $strType = 'static';
    
}
