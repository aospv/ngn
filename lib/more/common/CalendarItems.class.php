<?php

class CalendarItems extends Calendar {
  
  /**
   * Класс из которого берутся данные для генерации ссылки
   *
   * @var DdItems
   */
  public $oItems;
  
  /**
   * Массив с днями, для которых существуют данные
   * Пример структуры массива, где ключ - это день:
   * array(
   *   3 => 1,
   *   15 => 1,
   *   28 => 1
   * )
   *
   * @var array
   */
  public $daysDataExists;

  /**
   * Текущий путь страницы
   *
   * @var strgin
   */
  public $currentPath;
  
  public function __construct($currentPath, DdItems $oItems) {
    $this->currentPath = $currentPath;
    $this->oItems = $oItems;
    $this->setStartDay(1); // Устанавливаем первый день недели - понедельник
  }
  
  public function getMonthView($month, $year) {
    $this->daysDataExists = $this->oItems->getMonthDaysDataExists($month, $year);
    return parent::getMonthView($month, $year);
  }
  
  public function getDateLink($day, $month, $year) {
    if (in_array($day, $this->daysDataExists))
      return $this->currentPath."/d.$day.$month.$year";
  }
  
}
