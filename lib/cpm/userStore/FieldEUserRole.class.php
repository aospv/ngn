<?php

class FieldEUserRole extends FieldESelect {

  protected function defineOptions() {
    $this->options['options'] = Arr::get(UsersCore::getRoles(), 'title', 'name');
  }

}
