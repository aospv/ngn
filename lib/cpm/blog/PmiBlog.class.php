<?php

class PmiBlog extends PmiDd {
  
  public $title = 'Блог';
  public $oid = 20;
  
  protected $ddFields = array(
    array(
      'title' => 'Заголовок',
      'name' => 'title',
      'type' => 'typoText'
    ),
    array(
      'title' => 'Текст',
      'name' => 'text',
      'type' => 'wisiwig'
    )
  );

}
