<?php

class PageBlockStructure_usersOnline extends PageBlockStructureAbstract {
  
  static public $title = 'Пользователи онлайн';
  
  protected function initFields() {
    $this->fields[] = array(
      'name' => 'activeTime',
      'title' => 'Время активности',
      'descr' => 'После истечения какого времени неактивности пользователя считать его оффлайн',
      'type' => 'select',
      'required' => true,
      'options' => array(
        1 => '1 минута',
        2 => '2 минуты',
        3 => '3 минуты',
        4 => '4 минуты',
        5 => '5 минут',
        6 => '6 минут',
        7 => '7 минут',
        10 => '10 минут'
      )
    );
  }
  
}