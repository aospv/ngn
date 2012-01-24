<?php

class PmiArticles extends PmiDd {
  
  public $title = 'Статьи';
  public $oid = 60;
  
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
