<?php

class FieldEFontFamily extends FieldESelect {

  protected function defineOptions() {
    $this->options['options'] = array_merge(
      array('' => 'по умолчанию'),
      Arr::to_options(array(
      'Times New Roman',
      'Arial',
      'Tahoma',
      'Georgia',
      'Courier New',
    )));
  }

}
