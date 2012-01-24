<?php

class NewPageForm extends Form {

  protected $pageId;
  
  public function __construct($pageId) {
    $this->pageId = $pageId;
    $this->options['submitTitle'] = 'Создать';
    parent::__construct(new Fields(array(
      array(
        'title' => 'Название раздела',
        'name' => 'title',
        'required' => true
      ),
      array(
        'title' => 'Имя страницы',
        'help' => 'будет использоваться для формирования адреса страницы',
        'name' => 'name'
      ),
      array(
        'title' => 'Папка',
        'name' => 'folder',
        'type' => 'boolCheckbox'
      ),
      array(
        'type' => 'pageController',
        'name' => 'controller'
      )
    )));
  }
  
  protected function _update(array $data) {
    $data['parentId'] = $this->pageId;
    DbModelCore::create('pages', $data);
  }
  
}