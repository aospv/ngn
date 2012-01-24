<?php

class PcpDdItem extends PcpDd {
  
  public $title = 'Запись';
  
  public function getProperties() {
    $pr = parent::getProperties();
    $pr = Arr::dropBySubKey($pr, 'name', 'k');
    return $pr;
  }
  
}