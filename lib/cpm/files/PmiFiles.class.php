<?php

class PmiFiles extends PmiDd {
  
  public $title = 'Файлы';
  public $oid = 70;
  
  protected $ddFields = array(
    array(
      'title' => 'Название',
      'name' => 'title',
      'type' => 'typoText',
      'required' => true
    ),
    array(
      'title' => 'Файл',
      'name' => 'file',
      'type' => 'file'
    ),
    array(
      'title' => 'Закачки',
      'name' => 'file_dl',
      'type' => 'num',
      'system' => true
    ),
  );

  protected function getSettings() {
    return array_merge(
      parent::getSettings(),
      array(
        'itemTitle' => 'файл',
      )
    );
  }

  protected $ddLayoutShow = array(
    'adminItems' => array (
      'title' => 1,
      'file' => 1,
      'file_dl' => 1
    ),
    'siteItems' => array (
      'title' => 1,
    ),
    'pageBlock' => array (
      'title' => 1,
    ),
  );  
  
}