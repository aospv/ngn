<?php

class PmiProfileSimple extends PmiProfile {
  
  public $title = 'Профиль (простой)';
  public $oid = 120;
  
  protected $ddFields = array(
    array(
      'title' => 'Имя',
      'name' => 'name',
      'type' => 'typoText'
    ),
    array(
      'title' => 'Фото',
      'name' => 'image',
      'type' => 'ddUserImage'
    )
  );  
  
} 
