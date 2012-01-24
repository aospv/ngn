<?php

class PcpMyProfile extends PcpDd {

  public $title = 'Мой профиль';

  public $editebleContent = false;

  public function getProperties() {
    $pr = Arr::dropBySubKey(parent::getProperties(), 'name', 'premoder');
    return Arr::append($pr, array(
      array(
        'name' => 'mastersProfile', 
        'title' => 'Заголовок во множественном числе' 
      ),
      array(
        'name' => 'pluralTitle', 
        'title' => 'Заголовок во множественном числе' 
      ),
    ));
  }

}
