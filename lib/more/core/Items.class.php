<?php

class Items extends DbItems {

  public $isComments = true;

  protected $id;
  
  public function __construct($table) {
    parent::__construct($table);
    $this->id = R::set('n', (int)R::get('n') + 1);
  }
  
  protected function setTable($table) {
    $this->table = $table;
  }
  
  public function getItem($id) {
    return $this->getItem_nocache($id);
  }
  
  public function getItemNonFormat($id) {
    return $this->getItem($id);
  }
  
  public $strict = false;

  public function getItem_nocache($id) {
    $r = db()->selectRow(
      "
      SELECT
        {$this->table}.*,
        UNIX_TIMESTAMP({$this->table}.dateCreate) AS dateCreate_tStamp,
        UNIX_TIMESTAMP({$this->table}.dateUpdate) AS dateUpdate_tStamp,
        UNIX_TIMESTAMP({$this->table}.datePublish) AS datePublish_tStamp,
        UNIX_TIMESTAMP({$this->table}.commentsUpdate) AS commentsUpdate_tStamp,
        users.id AS authorId,
        users.login AS authorLogin,
        users.name AS authorName,
        pages.title AS pageTitle,
        pages.name AS pageName,
        pages.path AS pagePath
        {$this->selectCond}
      FROM {$this->table}
      LEFT JOIN users ON {$this->table}.userId=users.id
      LEFT JOIN pages ON {$this->table}.pageId=pages.id
      WHERE
        {$this->table}.id=?d
        ", $id);
    if ($this->strict and !$r)
      throw new NgnException("Item table={$this->table} with id={$id} does not exists");
    return Arr::unserialize($r);
  }
  
  protected function getItemCacheTags($id) {
    return array('item_'.$id);
  }
  
  public function getItemF($id) {
    return $this->getItem($id);
  } 

  public function getItemByField($key, $val) {
    $this->setActiveCond();
    $r = db()->selectRow(
      "
      SELECT
        {$this->table}.*,
        UNIX_TIMESTAMP({$this->table}.dateCreate) AS dateCreate_tStamp,
        UNIX_TIMESTAMP({$this->table}.dateUpdate) AS dateUpdate_tStamp,
        UNIX_TIMESTAMP({$this->table}.datePublish) AS datePublish_tStamp,
        UNIX_TIMESTAMP({$this->table}.commentsUpdate) AS commentsUpdate_tStamp,
        users.id AS authorId,
        users.login AS authorLogin,
        users.name AS authorName
        {$this->selectCond}
      FROM {$this->table}
      LEFT JOIN users ON {$this->table}.userId=users.id
      WHERE
        {$this->table}.$key=?d
        ", $val);
    foreach ($r as &$v) { 
      if (!empty($v) and strstr($v, '{'))
        $v = unserialize($v);
    }
    return $r;
  }

  // Используются в getCacheId
  private $rangeFrom;

  private $rangeTo;

  public function setActiveCond() {
    $this->addF('active', 1);
  }

  public function setN($n) {
    if (($n = (int)$n) != 0) $this->n = $n;
  }

  public function setPagination($flag) {
    $this->isPagination = $flag;
  }

  /**
   * Получать неактивные записи тоже в любом случае
   *
   * @var bool
   */
  protected $getNonActive = false;

  public function getItems() {
    if (!($items = $this->getItems_nocache())) return array();
    foreach ($items as $k => $item)
      foreach ($item as $kk => $v)
        if (!is_array($v) and Misc::unserializeble($v))
          $items[$k][$kk] = unserialize($v);
    return $items;
  }
  
  public function getItems_cache() {
    $cache = NgnCache::c();
    if (($items = $cache->load($this->getCacheId())) === false) {
      $items = $this->getItems_nocache();
      if (!empty($items)) $cache->save($items, $this->getCacheId());
    }
    return $items;
  }

  protected $commentsCondS;
  protected $commentsCondJ;
  protected $votingCondS;
  protected $votingCondJ;
  
  private function setCommentsConds() {
    if ($this->isComments) {
      $this->commentsCondS = ', cc.cnt AS commentsCount';
      $this->commentsCondJ = "LEFT JOIN comments_counts AS cc ON " .
         "{$this->table}.pageId=cc.parentId AND " .
         "{$this->table}.id=cc.id2 ";
    }
  }
  
  protected $cacheId;

  public function getCacheId() {
    if (isset($this->cacheId)) return $this->cacheId;
    $this->prepareItemsConds();
    $params = array(
      $this->table
    );
    if ($this->rangeFrom) {
      $params[] = $this->rangeFrom;
      $params[] = $this->rangeTo;
    }
    // Параметры сортировки
    $params[] = $this->orderKey;
    $params[] = (int) $this->orderAsc;
    // Параметр лимита
    $params[] = str_replace(',', '_', 
      preg_replace('/LIMIT (.*)/', '$1', 
        $this->limitCond)
    );
    // ---------------------------------------------------
    if ($this->filterCond) {
      $filterCond = $this->filterCond;
      // Получаем параметр активности
      preg_match('/`active` IN \((\d+)\)/', $filterCond, $m);
      if (isset($m[1])) $params[] = $m[1];
      // Удаляем условие активности
      $filterCond = preg_replace('/^.*`active`.*$/m', '', 
        $filterCond);
      // Получаем параметры фильтров
      preg_match_all('/`(.*)` IN \((\'(.*)\'|(.*))\)/', 
        $filterCond, $m);
      // Либо $m[3][1], либо $m[4][0]
      for ($i = 0; $i < count($m[0]); $i++) {
        $name = $m[1][$i];
        $k = $m[3][$i] ? Misc::translate($m[3][$i]) : $m[4][$i];
        $params[] = $name . $k;
      }
    }
    array_walk($params, function (&$v) {
      $v = str_replace(' ', '_', $v);
      $v = str_replace(',', '_', $v);
    });
    $cacheId = str_replace('__', '_', substr(implode('_', $params), 0, 255));
    return $cacheId;
  }

  public function getItems_nocache() {
    $this->prepareItemsConds();
    $q = "
    SELECT SQL_CACHE
      pages.strName,
      {$this->table}.*,
      UNIX_TIMESTAMP({$this->table}.dateCreate) AS dateCreate_tStamp,
      UNIX_TIMESTAMP({$this->table}.dateUpdate) AS dateUpdate_tStamp,
      UNIX_TIMESTAMP({$this->table}.datePublish) AS datePublish_tStamp,
      UNIX_TIMESTAMP({$this->table}.commentsUpdate) AS commentsUpdate_tStamp,
      {$this->table}.id AS ARRAY_KEY,
      users.id AS authorId,
      users.login AS authorLogin,
      users.name AS authorName,
      pages.title AS pageTitle,
      pages.name AS pageName,
      pages.path AS pagePath
      {$this->commentsCondS}
      {$this->votingCondS}
      {$this->selectCond}
    FROM {$this->table}
    {$this->commentsCondJ}
    {$this->votingCondJ}
    ".$this->cond->all();
    return db()->query($q);
  }

  public function getMonths($fldName) {
    $months = array();
    $r = db()->select(
      "SELECT YEAR($fldName) AS y, MONTH($fldName) AS m FROM {$this->table}
       WHERE 1 {$this->monthsFilterCond}");
    foreach ($r as $v) {
      if (! isset($months[$v['y']]))
        $months[$v['y']][] = $v['m'];
      elseif (! in_array($v['m'], $months[$v['y']])) {
        $months[$v['y']][] = $v['m'];
      }
    }
    return $months;
  }

  public function create(array $data) {
    if (empty($data['datePublish'])) $data['datePublish'] = dbCurTime();
    $data['commentsUpdate'] = dbCurTime();
    $data['ip'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    $id = parent::create($data);
    //$this->event('createItem', $id);
    return $id;
  }
  
  public function activate($id) {
    db()->query("UPDATE {$this->table} SET active=1 WHERE id=?d", $id);
    //$this->event('activateItem', $id);
  }

  public function deactivate($id) {
    db()->query("UPDATE {$this->table} SET active=0 WHERE id=?d", $id);
    //$this->event('deactivateItem', $id);
  }

  public function shiftUp($id) {
    DbShift::item($id, 'up', $this->table);
  }

  public function shiftDown($id) {
    DbShift::item($id, 'down', $this->table);
  }

  ////////////// Events /////////////
  
  public $disableEvents = false;
  
  public $eventUserId = 0;

  public function event($name, $id) {
    if ($this->disableEvents) return;
    $data = $this->getItemF($id);
    if (!isset($data['pageId'])) return;
    $data['itemId'] = $data['id'];
    if ($name == 'createItem') {
      ModerEventManager::event(
        $data['pageId'],
        isset($this->eventUserId) ? $this->eventUserId : Auth::get('id'),
        $name,
        $data
      );
    }
    Events::create($data['pageId'], Auth::get('id'), $name, $data);
  }
  
  protected function prepareItemsConds() {
    if ($this->itemsCondsPrepared) return;
    $this->cond->addJoin('users', 'userId');
    $this->cond->addJoin('pages', 'pageId');
    if (!$this->getNonActive)
      $this->setActiveCond(); // $activeCond - должно стоять первым в запросе
    parent::prepareItemsConds();
    $this->setCommentsConds();
    //$this->setVotingConds();
  }

}