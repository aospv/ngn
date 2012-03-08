<?php

class FieldEFontFamily extends FieldESelect {

  protected function defineOptions() {
    $this->options['options'] = array_merge(
      array('' => 'по умолчанию'),
      Arr::to_options(array(
      'Times New Roman',
      'Arial',
      array(
        'Arial Narrow',
        'Arial Narrow, Liberation Sans Narrow'
      ),
      'Tahoma',
      'Georgia',
      'Courier New',
    )));
  }

}
