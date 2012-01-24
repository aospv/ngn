<?php

class UserRoleForm extends Form {

  protected $userId;

  public function __construct($userId) {
    $this->userId = $userId;
    parent::__construct(new Fields(array(
      array(
        'name' => 'role',
        'title' => 'Тип профиля',
        'type' => 'userRole'
      )
    )));
    $user = DbModelCore::get('users', $this->userId);
    $this->setElementsData(array('role' => $user['role']));
  }
  
  protected function _update(array $data) {
    DbModelCore::update('users', $this->userId, $data);
  }

}