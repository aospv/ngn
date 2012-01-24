<?php

class PmiNews2 extends PmiDd {
  
  public $title = 'Новости 2';
  public $oid = 21;
  
  protected $ddFields = array(
    array(
      'title' => 'Заголовок',
      'name' => 'title',
      'type' => 'typoText',
      'required' => true
    ),
    array(
      'title' => 'Текст',
      'name' => 'text',
      'type' => 'wisiwig'
    )
  );
  
  protected $ddLayoutShow = array(
    'siteItems' => array (
      'title' => 1,
      'text' => 1,
      'datePublish' => 1
    ),
    'siteItem' => array (
      'text' => 1,
      'datePublish' => 1
    ),
    'adminItems' => array (
      'title' => 1,
      'text' => 1,
    ),
    'pageBlock' => array (
      'datePublish' => 1,
      'title' => 1,
      'text' => 1,
    ),
  );
  
  protected $ddLayoutOrderIds = array(
  );
  
  protected $ddLayoutOutputMethods = array(
    'siteItems' => array(
      'text' =>  'cut300',
      'datePublish' => 'datetime'
    ),
    'siteItem' => array(
      'datePublish' => 'datetime',
    ),
    'pageBlock' => array(
      'text' =>  'cut200',
    )
  );
  
  protected function getSettings() {
    return array_merge(
      parent::getSettings(),
      array(
        'dateField' => 'datePublish',
        'itemTitle' => 'новость',
      )
    );
  }

}
