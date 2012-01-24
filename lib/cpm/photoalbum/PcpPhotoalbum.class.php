<?php

class PcpPhotoalbum extends PcpItemsMaster {

  public $title = 'Альбомы';

  public function getProperties() {
    return Arr::append(parent::getProperties(), array(
      array(
        'name' => 'mozaicW', 
        'title' => 'Ширина мозаики', 
        'type' => 'num', 
      ),
      array(
        'name' => 'mozaicH', 
        'title' => 'Высота мозаики', 
        'type' => 'num', 
      ),
      array(
        'name' => 'mozaicElW', 
        'title' => 'Ширина элемента мозаики', 
        'type' => 'num' 
      ),
      array(
        'name' => 'mozaicElH', 
        'title' => 'Высота элемента мозаики', 
        'type' => 'num' 
      )
    ));
  }

}