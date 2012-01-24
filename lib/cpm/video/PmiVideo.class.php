<?php

class PmiVideo extends PmiDd {
  
  public $title = 'Видео';
  public $oid = 70;
  
  protected $ddFields = array(
    array(
      'title' => 'Название',
      'name' => 'title',
      'type' => 'typoText',
      'required' => true
    ),
    array(
      'title' => 'Видео',
      'name' => 'video',
      'type' => 'video',
      'required' => true
    ),
    array(
      'title' => 'Описание',
      'name' => 'text',
      'type' => 'wisiwig'
    )
  );

  protected function getSettings() {
    return array_merge(
      parent::getSettings(),
      array(
        'ddItemsLayout' => 'tile',
        'comments' => true,
        'smW' => 260,
        'smH' => 185,
        'mdW' => 540,
        'mdH' => 405,
        'itemTitle' => 'видео'
      )
    );
  }
  
  protected $ddLayoutShow = array(
    'siteItems' => array (
      'title' => 1,
      'video' => 1,
    ),
  );
  
  protected $ddLayoutOrderIds = array(
    'siteItems' => array(
      'video' => 10,
      'commentsCount' => 20,
      'title' => 30,
      'country' => 40,
      'dateCreate' => 50,
      'dateUpdate' => 60,
      'datePublish' => 70,
      'commentsUpdate' => 80,
      'userId' => 90,
      'clicks' => 100,
    )
  );
  
}
