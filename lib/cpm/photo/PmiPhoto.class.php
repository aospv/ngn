<?php

class PmiPhoto extends PmiDd {
  
  public $title = 'Фото';
  public $oid = 70;
  
  protected $ddFields = array(
    array(
      'title' => 'Название',
      'name' => 'title',
      'type' => 'typoText'
    ),
    array(
      'title' => 'Изображение',
      'name' => 'image',
      'type' => 'imagePreview',
      'required' => true
    )
  );

  protected function getSettings() {
    return array_merge(
      parent::getSettings(),
      array(
        'ddItemsLayout' => 'tile',
        'itemTitle' => 'фото',
        'mdW' => 800,
        'mdH' => 500,
      )
    );
  }
  
  protected $ddLayoutOrderIds = array(
    'siteItems' => array(
      'image' => 10,
      'title' => 20,
    )
  );
  
  protected $ddLayoutOutputMethods = array(
    'adminItems' => array (
      'image' => 'lightbox',
    ),
    'siteItems' => array (
      'image' => 'lightbox',
    ),
  );
  
  protected $pageLayout = 2;
  
}
