<?php

/**
 * ddItem       - запись подготовленная для создания DD_Imopter'ом
 * listPageItem - запись полученная после парсинга страницы со списком записей
 * contentItem  - запись полученная после парсинга страницы с содержанием записи
 */
abstract class GrabberSourceAbstract {
  
  public $limit;

  public $error;

  public $channelId;

  protected $data;

  public $channelData;

  public $pageId;

  public $pageSettings;
  
  public $itemsPerPage;
  
  public $unknownTotalCount = true;

  public function __construct($channelId) {
    $this->channelId = $channelId;
    $this->data = db()->selectRow('SELECT * FROM grabberChannel WHERE id=?d', $channelId);
    $this->channelData = unserialize($this->data['data']);
    $this->limit = Config::getVarVar('grabber', 'itemsLimit');
    $this->pageId = $this->data['pageId'];
    $this->pageSettings = DbModelCore::get('pages', $this->data['pageId'])->r['settings'];
    $this->init();
  }
  
  protected function init() {
  }
  
  /**
   * Скачивает данные из всех сохраненных ссылок
   */
  public function getDdItems() {
    $items = array();
    $n = 0;
    foreach ($this->getSavedListPageItems(true) as $listPageItem) {
      $items[] = $this->downloadDdItemByListPageItem($listPageItem);
      $n++;
      if ($n == $this->limit) break;
    }
    return $items;
  }
  
  /**
   * Возвращает записи с первой странимцы канала без скачивания материалов
   *
   * @return array
   */
  public function getTestDdItems() {
    $this->saveListPageItems(0);
    return $this->getSavedListPageItems();
    
    return;
    
    if (!empty($r)) {
      $item = Arr::first($r);
      if (!isset($item['title'])) throw new NgnException('=(');
      if (!isset($item['link'])) throw new NgnException('=(');
      if (!isset($item['text'])) throw new NgnException('=(');
    }
    return $r;
  }
  
  abstract public function downloadDdItemByListPageItem(array $listPageItem);
  
  public function getChannelData() {
    return $this->channelData;
  }
  
  static public function getVisibilityConditions() {
    return array();
  }
  
  /**
   * Проверяет наличие параметра 'link' в массиве записей и
   * возвращает массив списка записей на странице №$page
   *
   * @param integer #страницы
   */
  public function getListPageItems($page) {
    $items = $this->_getListPageItems($page);
    Misc::checkArray($items);
    foreach ($items as $k => $item)
      if (empty($item['link']))
        throw new NgnException('Item #'.$k.' "link" param is empty. $item: '.getPrr($item), 1006);
    return $items;
  }
  
  /**
   * Возвращает массив записей на странице №$page. Массив должен содержать элемент 'link'
   */
  abstract protected function _getListPageItems($page);
  
  /**
   * Возвращает общее кол-во записей в канале
   */
  abstract public function getItemsCount();
  
  /**
   * Возвращает общее кол-во страниц в канале
   */
  abstract public function getPagesCount();
  
  /**
   * Загружает страницу по ссылке $link и возвращает dd-запись
   *
   * @param string
  abstract protected function getDdItemByLink($link);
  
  public function createItemByLink($link) {
    return $this->createItem(
      // Скачиваем страницу
      $this->getDdItemByLink($link)
    );
  }
   */
  
  /**
   * Сохраняет записи со страницы #$page или получает уже 
   * сохраненный список и возвращает его
   *
   * @param   integer   # страницы
   * @return  array
   */
  public function saveListPageItems($page) {
    if (!($items = $this->getListPageItems($page))) {
      return false;
    }
    // Проверяем есть ли в записях со страницы №$page новые
    $savedItems = Arr::get(Settings::get('gslItems'.$this->channelId), 'link');
    foreach ($items as $item) {
      if (!in_array($item['link'], $savedItems)) {
        $item['timeSave'] = time();
        $item['page'] = $page;
        $newItems[] = $item;
      }
    }
    // И если есть сохраняем их
    if (!empty($newItems)) {
      Settings::addArray(
        'gslItems'.$this->channelId,
        $newItems
      );
      $this->lastSavedItems = $newItems;
      return true;
    }
    return false;
  }
  
  protected $lastSavedItems = array();
  
  public function getLastSavedItems() {
    return $this->lastSavedItems;
  }
  
  public function getSavedListPageItems($orderLastSaved = false) {
    if ($orderLastSaved) {
      return Arr::sort_by_order_key(
        Settings::get('gslItems'.$this->channelId), 'timeSave', SORT_DESC);
    } else {
      return Settings::get('gslItems'.$this->channelId);
    }
  }
  
  /**
   * Сохраняет все записи полученные в результате парсинга всех страниц с
   * записями ресурса
   */
  public function saveListPageItemsAll() {
    $page = 0;
    while ($this->saveListPageItems($page)) {
      $page++;
    }
  }

}
