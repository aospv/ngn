<?php

class FieldEFontStyle extends FieldESelect {

  protected function defineOptions() {
    $this->options['options'] = array(
      '' => 'по умолчанию',
      'italic' => 'наклонный',
      'normal' => 'обычный',
    );
  }

}
