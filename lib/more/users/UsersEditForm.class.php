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
  
  protected $fields = array(
    array(
      'name' => 'login',
      'title' => 'Логин',
      'type' => 'regLogin',
      'required' => true
    ),
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
      'required' => true
    )
  );
  
  protected function defineOptions() {
    parent::defineOptions();
    $this->options['submitTitle'] = 'Сохранить';
  }
  
  public function __construct($userId, array $options = array()) {
    $this->userId = $userId;
    if (!($this->user = DbModelCore::get('users', $this->userId)))
      throw new NgnException("User ID={$this->userId} does not exists");
    parent::__construct($options);
    $this->setElementsData(Arr::dropK($this->user->toArray(), 'pass'));
  }
  
  protected $filterFields = array('login', 'user', 'pass', 'email', 'name');
  
  protected function _update(array $data) {
    $data = Arr::filter_by_keys($data, $this->filterFields);
    Arr::filter_empties($data);
    $this->user->setArray($data)->save();
    $this->afterUserUpdate($this->userId, $data);
  }

}
