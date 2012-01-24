<?php

class FieldEColor extends FieldEText {

  public function _html() {
    return Tt::getTpl('common/colorPicker', 
      array(
        'default' => $this->options['value'], 
        'name' => $this->options['name'],
        'classAtr' => $this->getClassAtr()
      )
    );
  }
  
}