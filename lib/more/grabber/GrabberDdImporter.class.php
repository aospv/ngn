<?php

class GrabberDdImporter {

  /**
   * @var DdItemsManager
   */
  public $manager;
  
  /**
   *
   * @var GrabberSourceAbstract
   */
  protected $oGS;

  public function __construct(GrabberSourceAbstract $oGS) {
    $this->oGS = $oGS;
  }
  
  protected function initDdManager() {
    if (isset($this->manager)) return;
    if (!$this->oGS->pageSettings['strName'])
      throw new NgnException("settings['strName'] not defined", 1002);
    $this->manager = DdItemsManager::getObjDef(
      $this->oGS->pageSettings['strName'], 
      $this->oGS->pageId
    );
    $this->manager->defaultActive = !empty($this->oGS->pageSettings['premoder']) ? 0 : 1;
    $this->manager->oItems->forceDublicateInsertCheck = true;
    if (!constant('RSS_ROBOT_ID'))
      throw new NgnException('RSS_ROBOT_ID is empty', 1003);
    $this->manager->setAuthorId(RSS_ROBOT_ID);
    $this->manager->typo = false;
  }
  
  /**
   * @var NgnValidError
   */
  public $validError;
  
  /**
   * Создает dd-запись
   *
   * @param   array   Пример:
   *                  array(
   *                    'title' => '...',
   *                    'text' => '........',
   *                    'link' => 'http://',
   *                    'dateCreate' => '02.09.2010 14:50:06',
   *                    'datePublish' => '02.09.2010 14:50:06'
   *                  )
   * @return  bool
   */
  protected function createDdItem(array $item) {
    $this->initDdManager();
    unset($item['link']);
    // Если запись с таким ключем уже присутствует, игнорируем вставку
    if ($this->itemExists($item)) {
      $this->validError = new NgnValidError('Item '.getPrr($item).' aleady exists', 4333);
      output($this->validError->getMessage());
      return false;
    }
    output('create dd item');
    $this->manager->forceVideoEncode = true;
    // Создаём запись
    if (!($itemId = $this->manager->create($item))) {
      $this->validError = new $this->manager->validError;
      output(Misc::translate($this->validError->getMessage()));
      return false;
    }
    // Сохраняем ключ записи
    $this->storeKey($this->manager->strName, $itemId, $item);
    return $itemId;
  }
  

  protected function getKey($item) {
    return md5(serialize($item));
  }

  /**
   * @param   array   $item
   * @return  bool
   */
  private function itemExists($item) {
    return db()->query("SELECT k FROM grabber_keys WHERE k=?", 
      $this->getKey($item)) ? true : false;
  }

  private function storeKey($strName, $itemId, $item) {
    db()->query("INSERT INTO grabber_keys SET strName=?, itemid=?d, k=?, dateCreate=?", 
      $strName, $itemId, $this->getKey($item), dbCurTime());
  }
  
  protected function beforeImport() {
    db()->query('UPDATE grabberChannel SET dateLastCheck=? WHERE id=?d', 
      dbCurTime(), $this->oGS->channelId);
  }
  
  /*
  protected function afterImport($grabbed) {
    // Сохраняем число сохраненных
    if ($this->grabbed) {
      db()->query('UPDATE grabberChannel SET dateLastGrab=?, lastGrabbed=?d WHERE id=?d', 
        dbCurTime(), $grabbed, $this->channelId);
    }
    // Обнуляем кол-во неуспешных попыток, если они были
    if ($this->data['attempts'] > 0)
      db()->query('UPDATE grabberChannel SET attempts=0 WHERE id=?d', $this->channelId);
  }
  */
  
  public function importPageListItem($n) {
    $this->beforeImport();
    if (!($listPageItems = $this->oGS->getSavedListPageItems()))
      throw new NgnException('No saved list page items', 1004);
    if (!($ddItem = $this->oGS->downloadDdItemByListPageItem($listPageItems[$n])))
      return false;
    if (!$this->createDdItem($ddItem)) {
      throw new NgnException(
        $this->validError->getMessage().'<pre>'.getPrr($ddItem).'</pre>',
        $this->validError->getCode()
      );
    }
  }

  protected function importListPageItems(array $listPageItems) {
    $ddItemIds = array();
    foreach ($listPageItems as $listPageItem) {
      if (!($ddItem = 
        $this->oGS->downloadDdItemByListPageItem($listPageItem))) continue;
      if (($id = $this->createDdItem($ddItem))) {
        $ddItemIds[] = $id;
      }
    }
    return $ddItemIds;
  }

  /**
   * Получает список всех сохраненных ссылок
   * Скачивает запись для каждой из них
   * Создаёт соответствующие дд-записи
   *
   * @return array  ID созданных dd-записей
   */
  public function importAllSaved() {
    $this->beforeImport();
    if (!($listPageItems = $this->oGS->getSavedListPageItems()))
      throw new NgnException('No saved list page items', 1005);
    return $this->importListPageItems($listPageItems);
  }
  
  /**
   * Сохраняет новые записи с первой страницы канала
   * Скачивает запись для каждой из них
   * Создаёт соответствующие дд-записи
   * 
   * @return array  ID созданных dd-записей
   */
  public function importNew() {
    $this->beforeImport();
    if ($this->saveListPageItems(0)) return false;
    return $this->importListPageItems($this->oGS->getLastSavedItems());
  }
  

}