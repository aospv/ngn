<?php

class UsersEditForm extends UsersRegForm {

  /**
   * ID редактируемого пользователя
   *
   * @var integer
   */
  protected $userId;
  
  /**
   * @var Doctrine_Record
   */
  protected $user;
  
  protected function defineOptions() {
    parent::defineOptions();
    $this->options['submitTitle'] = 'Сохранить';
  }
  
  public function __construct($userId, array $options = array()) {
    $this->userId = $userId;
    if (!($this->user = DbModelCore::get('users', $this->userId)))
      throw new NgnException("User ID={$this->userId} does not exists");
    parent::__construct($options);
    $this->setElementsData(Arr::dropK($this->user->r, 'pass'));
  }
  
  protected function _getFields() {
    return array(
      UserRegCore::getLoginField(),
      array(
        'name' => 'passBegin',
        'title' => 'Изменить пароль',
        'type' => 'headerToggle'
      ),
      array(
        'name' => 'pass',
        'title' => 'Пароль',
        'help' => 'Оставьте пустым, если не хотите менять',
        'type' => 'password'
      ),
      array(
        'type' => 'headerClose'
      ),
      array(
        'name' => 'email',
        'title' => 'Ящик',
        'type' => 'email',
      ),
      array(
        'name' => 'phone',
        'title' => 'Телефон',
        'type' => 'phone',
      ),
    );    
  }
  
  protected $filterFields = array('login', 'user', 'pass', 'email', 'name');
  
  protected function _update(array $data) {
    DbModelCore::update('users', $this->userId, $data);
    $this->afterUserUpdate($this->userId, $data);
  }

}
