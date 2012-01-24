<?php

class FieldETagFields extends FieldESelect {

  protected $allowedFormClass = 'DdFormBase';

  protected function defineOptions() {
    $this->options['options'] = array('' => '— '.LANG_NOTHING_SELECTED.' —');
    foreach (O::get('DdFields', $this->oForm->strName)->getTagFields() as $v)
      $this->options['options'][$v['name']] = $v['title'];
  }

}
