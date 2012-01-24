<?php

class FieldEHiddenWithRow extends FieldEHidden {

  protected function defineOptions() {
    parent::defineOptions();
    $this->options['noRowHtml'] = false;
  }
  
}