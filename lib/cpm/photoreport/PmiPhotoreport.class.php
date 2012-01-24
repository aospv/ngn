<?php

class PmiPhotoreport extends PmiPhotoalbum {
  
  public $title = 'Фоторепортаж';
  protected $masterStrName = 'photoreport';
  
  public function __construct(array $options = array()) {
    $this->masterFields[] = array(
      'title' => 'Дата съемки',
      'name' => 'date_photo',
      'type' => 'date'
    );
    parent::__construct($options);
  }
  
  protected function getSettings() {
    return array_merge(
      parent::getSettings(),
      array(
        'order' => 'date_photo'
      )
    );
  }
  
}
