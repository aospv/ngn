<?php

class CalendarAdv extends Calendar {
  
  /**
   * Класс из которого берутся данные для генерации ссылки
   *
   * @var object
   */
  public $oGetLink;
  
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
  
  function __construct($currentPath, $oGetLink) {
    $this->currentPath = $currentPath;
    $this->oGetLink = $oGetLink;
    $this->setStartDay(1); // Устанавливаем первый день недели - понедельник
  }
  
  function getMonthView($month, $year) {
    $this->setDaysDataExists($month, $year);
    return parent::getMonthView($month, $year);
  }
  
  function setDaysDataExists($month, $year) {
    $this->daysDataExists =& $this->oGetLink->getMonthDaysDataExists($month, $year, $_PAGE['id']);
  }
  
  function getDateLink($day, $month, $year) {
    if ($this->daysDataExists[$day]) return $this->currentPath."/date/$year/$month/$day";
  }
  
}

?>