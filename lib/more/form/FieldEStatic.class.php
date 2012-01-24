<?php

class FieldEStatic extends FieldEText {

  public $options = array(
    'noRowHtml' => true
  );

  public function html() {
    return '';
  }

}
