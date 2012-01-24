<?php

class FieldEFontSize extends FieldESelect {

  protected function defineOptions() {
    $this->options['options'] = array_merge(
      array('' => 'по умолчанию'),
      Arr::to_options(array(
        '9px',
        '10px',
        '11px',
        '12px',
        '13px',
        '14px',
        '15px',
        '16px',
        '18px',
        '20px',
        '22px',
        '24px',
        '28px',
        '32px',
        '40px',
    )));
  }

}