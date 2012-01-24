<?php

class FieldEUserRoles extends FieldEMultiselect {

  protected function defineOptions() {
    $this->options['options'] = Arr::get(UsersCore::getRoles(), 'title', 'name'); 
    parent::defineOptions();
  }

}