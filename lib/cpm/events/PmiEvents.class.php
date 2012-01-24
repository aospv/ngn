<?php

class PmiEvents extends PmiDd {

  public $title = 'События';
  public $oid = 20;
  
  protected $ddFields = array(
    array(
      'title' => 'Заголовок',
      'name' => 'title',
      'type' => 'typoText'
    ),
    array(
      'title' => 'Изображение',
      'name' => 'image',
      'type' => 'image'
    ),
    array(
      'title' => 'Анонс',
      'name' => 'pretext',
      'type' => 'wisiwig'
    ),
    array(
      'title' => 'Текст',
      'name' => 'text',
      'type' => 'wisiwig'
    ),
    array(
      'title' => 'Дата начала мероприятия',
      'name' => 'date_begin',
      'type' => 'date'
    ),
    array(
      'title' => 'Дата окончания мероприятия',
      'name' => 'date_end',
      'type' => 'date'
    ),
  );
    
  protected $ddLayoutShow = array(
    'siteItems' => array (
      'title' => 1,
      'image' => 1,
      'pretext' => 1,
      'date_begin' => 1,
      'date_end' => 1
    ),
    'siteItem' => array (
      'image' => 1,
      'text' => 1,
      'date_begin' => 1,
      'date_end' => 1
    ),
  );
  
}
