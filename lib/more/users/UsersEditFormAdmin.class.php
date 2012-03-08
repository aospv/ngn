<?php

class UsersEditFormAdmin extends UsersEditForm {

  protected function defineOptions() {
    parent::defineOptions();
    $this->filterFields[] = 'role';
  }
  
  protected function _getFields() {
  	return parent::_getFields();
  	return array_merge(parent::_getFields(), array(array(
  	  'name' => 'role',
  	  'title' => 'Тип профиля',
  	  'type' => 'role',
  	)));
  }
  
}
