<?php

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
    db()->create('storeCart', array(
      'sessionId' => $this->sessionId,
      'pageId' => $pageId,
      'itemId' => $itemId
    ), true);
  }
  
  public function delete($pageId, $itemId) {
    db()->query('DELETE FROM storeCart WHERE sessionId=? AND pageId=? AND itemId=?',
      $this->sessionId, $pageId, $itemId);
  }
  
  public function getIds() {
    return db()->query('SELECT pageId, itemId FROM storeCart WHERE sessionId=?', $this->sessionId);
  }
  
  public function getItems() {
    $items = DdCore::extendItemsData($this->getIds());
    return empty($items) ? false : $items;
  }
  
  public function clear() {
    db()->query('DELETE FROM storeCart WHERE sessionId=?', $this->sessionId);
    return $this;
  }
  
}