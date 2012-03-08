<?php

class PbsPastItems extends PbsItems {

  static public $title = 'Записи в прошлом';
  
  protected function initFields() {
    parent::initFields();
    $this->fields[] = array(
      'name' => 'dateField',
      'title' => 'Поле даты',
      'type' => 'select',
      'options' => DdFieldOptions::date($this->strName),
      'required' => true
    );
    $this->fields[] = array(
      'name' => 'period',
      'title' => 'Период',
      'type' => 'select',
      'required' => true,
      'options' => array(
        1 => '1 день',
        2 => '2 дня',
        3 => '3 дня',
        4 => '4 дня',
        5 => '5 дней',
        7 => 'неделя',
        14 => '2 недели',
        21 => '3 недели',
        30 => 'месяц',
        60 => '2 месяца',
        90 => '3 месяца',
        180 => '6 месяцев',
        365 => 'год',
        730 => '2 года',
        1825 => '5 лет'
      )
    );
  }

}