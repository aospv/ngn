<?php

class FieldEFontWeight extends FieldESelect {

  protected function defineOptions() {
    $this->options['options'] = array(
      '' => 'по умолчанию',
      'bold' => 'жирный',
      'normal' => 'обычный'
    );
  }

}
