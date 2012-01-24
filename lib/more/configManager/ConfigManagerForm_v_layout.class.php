<?php

class ConfigManagerForm_v_layout extends ConfigManagerForm {
  
  protected $maxImageSize = array(
    'v[logoImage]' => array(200, 50)
  );
  
  protected function afterUpdate(array $values) {
    //die2($values);
  }
  
}
