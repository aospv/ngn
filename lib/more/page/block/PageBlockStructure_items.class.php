<?php

class PageBlockStructure_items extends PageBlockStructureDdPage {

  static public $title = 'Записи';
  
  protected function initFields() {
    $this->fields[] = array(
      'name' => 'order',
      'title' => 'Сортировка',
      'type' => 'select',
      'required' => true,
      'options' => DdFieldOptions::order($this->strName)
    );
    $this->fields[] = array(
      'name' => 'listBtnOrder',
      'title' => 'Кнопка «все» ссылается на список записей',
      'type' => 'select',
      'required' => true,
      'options' => array(
        'default' => 'в порядке, определенном по умолчанию для раздела',
        'block' => 'в том же порядке что и в блоке',
      )
    );
    $this->fields[] = array(
      'name' => 'listBtnTitle',
      'title' => 'Заголовок кнопки «все»'
    );
    $this->fields[] = array(
      'name' => 'limit',
      'title' => 'Лимит',
      'type' => 'num',
      'required' => true
    );
  }
  
}