<?php

class FieldETextarea extends FieldEText {

  protected $staticType = 'textarea';
  protected $useDefaultJs = true;

  public $options = array(
    'maxlength' => 65000
  );
  
  public function _html() {
    return '<textarea name="'.$this->options['name'].'"'.
      Tt::tagParams($this->getTagsParams()).$this->getClassAtr().'>'.
      $this->options['value'].'</textarea>';
  }
  
}
