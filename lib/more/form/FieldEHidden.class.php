<?php

class FieldEHidden extends FieldEText {

  public $inputType = 'hidden';

  protected function defineOptions() {
    $this->options['type'] = 'hidden';
    $this->options['noRowHtml'] = true;
  }
  
}
