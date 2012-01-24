<?php

class FieldEWisiwig extends FieldETextarea {

  protected $staticType;

  public function defineOptions() {
    parent::defineOptions();
    $this->options['rowClass'] = 'elWisiwig';
  }
  
  protected function addRequiredCssClass() {
    if (!empty($this->options['required']))
      $this->cssClasses[] = 'required-wisiwig';
  }

}