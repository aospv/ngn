<?php

class FieldEDdDateFields extends FieldESelect {

  protected function defineOptions() {
    $this->options['options'] = DdFieldOptions::date($this->oForm->strName);
  }

}