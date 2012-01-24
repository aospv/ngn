<?php

DdFieldCore::registerType('photoalbum', array(
  'dbType' => 'VARCHAR',
  'dbLength' => '255',
  'title' => 'Выбор фотоальбома',
  'order' => 410,
));

class FieldEPhotoalbum extends FieldECreator {

  protected function defineOptions() {
    $this->options['fields'][] = array(
      'title' => 'Раздел',
      'name' => 'pageId',
      'type' => 'num'
    );
    $this->options['fields'][] = array(
      'title' => 'Альбом',
      'name' => 'albumId',
      'type' => 'num'
    );
  }

}