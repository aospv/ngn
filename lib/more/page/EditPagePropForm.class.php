<?php

class EditPagePropForm extends Form {

  protected $pageId;
  
  public function __construct($pageId, $god) {
    $this->pageId = $pageId;
    $this->options['filterEmpties'] = true;
    $fields = array(
      array(
        'title' => 'Название раздела',
        'name' => 'title',
        'required' => true
      ),
      array(
        'title' => 'Каталог',
        'name' => 'folder',
        'type' => 'boolCheckbox'
      ),
      array(
        'name' => 'linkSection',
        'type' => 'headerVisibilityCondition'
      ),
      array(
        'title' => 'Ссылка',
        'name' => 'link',
        'type' => 'pageLink',
      ),
      array(
        'title' => 'Дополнительные параметры',
        'type' => 'headerToggle'
      ),
      array(
        'title' => 'Имя страницы',
        'help' => 'будет использоваться для формирования адреса страницы',
        'name' => 'name'
      ),
      array(
        'title' => 'Полный заголовок раздела',
        'help' => 'используется, если заголовок раздела на странице боле длинный, чем в меню',
        'name' => 'fullTitle'
      ),
      array(
        'title' => 'Сделать главной страницей',
        'name' => 'home',
        'type' => 'boolCheckbox'
      ),
    );
    if ($god) {
      $fields[] = array(
        'title' => 'Модуль',
        'name' => 'module'
      );
      $fields[] = array(
        'type' => 'pageController',
        'name' => 'controller'
      );
    } else {
      $fields[] = array(
        'title' => 'Модуль',
        'name' => 'module',
        'type' => 'hidden'
      );
    }
    $this->addVisibilityCondition('linkSection', 'module', 'v == "link"');
    parent::__construct(new Fields($fields));
    $this->setElementsData(DbModelCore::get('pages', $this->pageId)->r);
  }
  
  protected function _update(array $data) {
    DbModelCore::update('pages', $this->pageId, $data);
  }

}
