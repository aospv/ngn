<?php

class UsersEditFormAdmin extends UsersEditForm {

  protected function defineOptions() {
    parent::defineOptions();
    $this->filterFields[] = 'role';
    $this->fields[] = array(
      'name' => 'role',
      'title' => 'Тип профиля',
      'type' => 'role',
    );
  }
  
}
