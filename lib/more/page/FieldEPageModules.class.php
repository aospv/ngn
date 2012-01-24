<?php

class FieldEPageModules extends FieldESelect {

  protected function defineOptions() {
    $this->options['options'] = array('' => '—');
    $this->options['options'] += Arr::get(O::get('PageModules')->getItems(), 'title', 'KEY');
  }

}