<?php

class PmiPhotoalbum extends PmiDdMaster {
  
  public $title = 'Фотоальбом';
  public $controller = 'photoalbum';
  public $oid = 80;
  protected $masterTitle = 'Альбомы';
  protected $masterFields = array(
    array(
      'title' => 'Превью',
      'name' => 'preview',
      'type' => 'static',
      'system' => true
    ),
    array(
      'title' => 'Название',
      'name' => 'title',
      'type' => 'typoText'
    )
  );
  protected $slaveTitle = 'фото';
  protected $slavePageName = 'sub';
  protected $slaveFields = array(
    array(
      'title' => 'Название',
      'name' => 'title',
      'type' => 'typoText',
    ),
    array(
      'title' => 'Изображение',
      'name' => PhotoalbumCore::slaveImageFieldName,
      'type' => 'imagePreview',
      'required' => true,
    )
  );
  
  protected function getSettings() {
    return array_merge(
      parent::getSettings(),
      array(
        'mozaicW' => '200',
        'mozaicH' => '100',
        'mozaicElW' => '100',
        'mozaicElH' => '50',
        'ddItemsLayout' => 'tile',
        'itemTitle' => 'альбом'
      )
    );
  }
  
  protected function getSlaveSettings() {
    return array(
      'ddItemsLayout' => 'tile',
      'itemTitle' => 'фото'
    );
  }
  
}
