<?php

class PmiNews extends PmiDd {
  
  public $title = 'Новости';
  public $oid = 20;
  
  protected $ddFields = array(
    array(
      'title' => 'Заголовок',
      'name' => 'title',
      'type' => 'typoText',
      'required' => true
    ),
    array(
      'title' => 'Изображение',
      'name' => 'image',
      'type' => 'imagePreview'
    ),
    array(
      'title' => 'Анонс',
      'name' => 'pretext',
      'type' => 'wisiwig',
      'required' => true
    ),
    array(
      'title' => 'Текст',
      'name' => 'text',
      'type' => 'wisiwig'
    ),
    array(
      'title' => 'dummy',
      'name' => 'text_body',
      'type' => 'floatBlock'
    ),
  );
  
  protected function getSettings() {
    return array_merge(
      parent::getSettings(),
      array(
        'dateField' => 'datePublish',
        'itemTitle' => 'новость',
        'smW' => 80,
        'smH' => 50,
        'mdW' => 200,
        'mdH' => 150
      )
    );
  }

}
