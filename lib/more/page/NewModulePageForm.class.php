<?php

class NewModulePageForm extends Form {
  
  protected $pageId;
  
  public function __construct($pageId) {
    $this->pageId = $pageId;
    $this->options['submitTitle'] = 'Создать';
    parent::__construct(new Fields(array(
      array(
        'title' => 'Тип раздела',
        'name' => 'module',
        'type' => 'select',
        'required' => true,
        'default' => 'content',
        'options' => array_merge(
          array('' => '— не задано —'),
          Arr::get(
            Misc::isGod() ?
              O::get('PageModules')->getItems() :
              O::get('PageModulesAllowed')->getItems()
          , 'title', 'KEY')
        )
      ),
      array(
        'title' => 'Название раздела',
        'name' => 'title',
        'required' => true
      ),
      array(
        'title' => 'Папка',
        'name' => 'folder',
        'type' => 'boolCheckbox'
      ),
      array(
        'name' => 'typeSection',
        'type' => 'headerVisibilityCondition'
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
    )));
    $this->addVisibilityCondition('typeSection', 'folderPageType', 'v != "empty"');
    $this->addVisibilityCondition('linkSection', 'module', 'v == "link"');
  }
  
  protected function _update(array $data) {
    $data['parentId'] = $this->pageId;
    Pmi::get($data['module'])->install($data);
  }

}