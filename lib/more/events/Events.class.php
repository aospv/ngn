<?php

class Events {
  
  public $pNums;
  
  public $n = 20;
  
  /**
   * @var EventsInfo_Items
   */
  protected $oEII;
  
  
  function __construct() {
    $this->oEII = new EventsInfo_Items();
  }
  
  function getItems() {
    Pagination::$n = $this->n;
    //Pagination::$whereCond = "events.name='createDDItem'";
    list($this->pNums, $limit) = Pagination::get('events');
    if ($limit) $limitCond = 'LIMIT '.$limit;
    $items = array();
    foreach (db()->select("
      SELECT
        events.data,
        events.name,
        events.dateCreate,
        UNIX_TIMESTAMP(events.dateCreate) AS dateCreate_tStamp,
        events.userId
      FROM events
      ORDER BY dateCreate DESC $limitCond") as $k => $v) {
      $v['data'] = !empty($v['data']) ? unserialize($v['data']) : null;
      //$v['data']['page']['pathData'] = unserialize($v['data']['page']['pathData']);
      $v['title'] = isset($this->oEII->events[$v['name']]) ? 
        $this->oEII->events[$v['name']]['title'] : null;
      $items[$k] = $v;
    }
    return $items;
  }
  
  static function create($pageId, $userId, $name, $data) {
    $data += Misc::getHttpClientInfo();
    $data['backtrace'] = getBacktrace(false);
    db()->query(
      "INSERT INTO events SET pageId=?d, userId=?d, name=?, data=?, dateCreate=?",
      $pageId, $userId, $name, serialize($data), dbCurTime());
  }
  
  static function deleteAll() {
    db()->query('DELETE FROM events');
  }
  
}