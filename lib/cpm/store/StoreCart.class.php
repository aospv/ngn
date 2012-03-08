<?php

/**
 * Объект маппится в контроллере
 */
class StoreCart {

  static public function get() {
    return new StoreCart();
  }
  
  protected $sessionId;
  
  public function __construct($sessionId = null) {
    $this->sessionId = $sessionId ? $sessionId : session_id();
    Misc::checkEmpty($this->sessionId);
  }

  public function add($pageId, $itemId) {
    db()->increment('storeCart', array(
      'sessionId' => $this->sessionId,
      'pageId' => $pageId,
      'itemId' => $itemId
    ), array(
      'dateUpdate' => dbCurTime()
    ));
  }
  
  public function delete($pageId, $itemId) {
    db()->query('DELETE FROM storeCart WHERE sessionId=? AND pageId=? AND itemId=?',
      $this->sessionId, $pageId, $itemId);
  }
  
  public function getIds() {
    return db()->query('SELECT pageId, itemId, cnt FROM storeCart WHERE sessionId=?', $this->sessionId);
  }
  
  public function getItems() {
    $items = array();
    foreach (DdCore::extendItemsData($this->getIds()) as $v)
      $items[$v['id']] = $v;
    return empty($items) ? false : $items;
  }
  
  public function updateCnt($pageId, $itemId, $cnt) {
    db()->query('UPDATE storeCart SET cnt=?d WHERE pageId=?d AND itemId=?d', $cnt, $pageId, $itemId);
  }
  
  public function clear() {
    db()->query('DELETE FROM storeCart WHERE sessionId=?', $this->sessionId);
    return $this;
  }
  
}