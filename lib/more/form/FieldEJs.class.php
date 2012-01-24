<?php

class FieldEJs extends FieldEAbstract {

  public $options = array(
    'noRowHtml' => true,
    'noValue' => true
  );

  public function _js() {
    return str_replace('{formId}', "'".$this->oForm->id."'", $this->options['js']);
  }

}
