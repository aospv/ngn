<?php

class PageControllerSettingsFields extends Fields {
  
  public function __construct($controller) {
    $fields = array();
    foreach (PageControllersCore::getPropObj($controller)->getProperties() as $k => $v) {
      $fields["page[settings][$k]"] = $v;
    }
    parent::__construct($fields);
  }
  
}