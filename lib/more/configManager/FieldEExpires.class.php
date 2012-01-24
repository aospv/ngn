<?php

class FieldEExpires extends FieldESelect {

  protected function defineOptions() {
    $this->options['options'] = array(
      60*60*24*1 => '1 день',
      60*60*24*2 => '2 дня',
      60*60*24*3 => '3 дня',
      60*60*24*7 => 'неделя',
      60*60*24*14 => '2 недели',
      60*60*24*30 => 'месяц',
      60*60*24*90 => '3 месяца',
      60*60*24*30*12 => 'год',
    );
  }

}