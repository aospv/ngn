<?php

class ItemsReporter extends Reporter {
  
  public $strName;
  
  public $place;
  
  public $limit = 5;
  
  protected $items;
  
  public $adsIds;
  
  public function __construct($strName, $dateField) {
    //parent::__construct('adslog', $dateField);
    $this->strName = $strName;
  }
  
  public function setPlace($place) {
    $this->place = $place;
  }
  
  public function setIds($ids) {
    if (!is_array($ids)) return;
    foreach ($ids as &$v) $v = (int)$v;
    $this->itemIds = $ids;
  }
  
  protected function setAds() {
    $oItems = new DdItems($this->strName);
    if (isset($this->place))
      $oItems->setExtraCond("AND place='{$this->place}'");
    if (!$items = $oItems->getItems($this->limit, $this->itemIds)) {
      throw new NgnException('No $items in '.$this->strName.' structure');
    }
    $this->items =& $items;
  }
  
  public function month($date, $title, $xTitle, $yTitle) {
    $this->setAds();
  }
  
  public function getBarX($type) {
    if ($type == 'day') {
      for ($i=0; $i<=23; $i++) $dataBarX[] = $i;      
    } elseif($type == 'month') {
      for ($i=1; $i<=31; $i++) $dataBarX[] = $i;
    } elseif ($type == 'year') {
      for ($i=1; $i<=12; $i++) $dataBarX[] = $i;
    } else Err::warning("Unknown type $type");
    return $dataBarX;    
  }
  
  public function table($type, $date) {
    $this->setAds();
    $dataBarX = $this->getBarX($type);
    $i = 0;
    foreach ($this->items as $v) {
      $v['color'] = $this->colors[$i];
      $rows[$i]['data'] = $v; 
      $this->resetExtraCond("AND adsId={$v['id']}");
      $rows[$i]['report'] = $this->getData($type, $date);
      $report =& $rows[$i]['report'];
      for ($j=0; $j<count($dataBarX); $j++) {
        $table[$dataBarX[$j]] = $report[$dataBarX[$j]] ? $report[$dataBarX[$j]] : '-';
      } 
      $report = $table;
      $i++;
    }    
    return $rows;
  }
  
  /**
   * Генерирует изображение графика и возвращает путь до него
   *
   * @param   string  Тип: day/month/year
   * @param   string  dd.mm.yyyy
   * @param   string  Заголовок графика
   * @param   string  Заголовок оси X
   * @param   string  Заголовок оси Y
   * @return  string  Путь к изображению
   */
  public function graph($type, $date, $title, $xTitle, $yTitle) {
    $this->setAds();
    $dataBarX = $this->getBarX($type);
    $i = 0;
    foreach ($this->items as $v) {
      $rows[$i]['data'] = $v; 
      $this->resetExtraCond("AND adsId={$v['id']}");
      $rows[$i]['report'] = $this->getData($type, $date);
      //die2($rows[$i]['report']);
      if (!$rows[$i]['report']) {
        return false;       
      }
      $i++;
    }
    return $this->_graph($rows, $dataBarX, $title, $xTitle, $yTitle);
  }
  
  public function day($date, $title, $xTitle, $yTitle) {
    return $this->graph('day', $date, $title, $xTitle, $yTitle);
  } 
  
}