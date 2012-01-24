<?php

class PcpProfiles extends PcpDdItems {

  public $title = 'Профили';
  
  public function getProperties() {
    return array_merge(array(array(
      'title' => 'Раздел профиля',
      'name' => 'myProfileId',
      'type' => 'pageId'
    //)), Arr::dropBySubKey(parent::getProperties(), 'name', 'strName'));
    )), parent::getProperties());
  }

}
