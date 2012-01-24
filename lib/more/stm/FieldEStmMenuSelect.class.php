<?php

class FieldEStmMenuSelect extends FieldESelect {

  protected function defineOptions() {
    $this->options['options'] = array('' => 'не определено');
    $this->options['options'] += Arr::get(StmCore::getMenuStructures(), 'title', 'KEY');
  }

}