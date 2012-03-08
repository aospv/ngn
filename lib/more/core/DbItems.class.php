<?php

class DbItems implements UpdatableItems {
  
  public $table;
  
  /**
   * @var Db
   */
  public $db;
  
  /**
   * @var DbCond
   */
  public $cond;
  
  public function __construct($table, Db $db = null) {
    $this->table = $table;
    $this->cond = new DbCond($table);
    $this->db = $db ? $db : db();
  }

  public function getItem($id) {
    return Arr::unserialize(
      $this->db->selectRow("SELECT * FROM {$this->table} WHERE id=?d", $id));
  }
  
  public function getItemByField($key, $val) {
    return Arr::unserialize(
      $this->db->selectRow("SELECT * FROM {$this->table} WHERE $key=?", $val));
  }
  
  public function create(array $data) {
    if (empty($data['dateCreate'])) $data['dateCreate'] = dbCurTime();
    $data['dateUpdate'] = dbCurTime();
    return $this->db->query("INSERT INTO {$this->table} SET ?a", Arr::serialize($data));
  }
  
  public function update($id, array $data) {
    $data['dateUpdate'] = dbCurTime();
    $this->db->query("UPDATE {$this->table} SET ?a WHERE id=?d", Arr::serialize($data), $id);
  }
  
  public function event($name, $id) {
  }

  public function getItemNonFormat($id) {
    return $this->getItem($id);
  }
  
  public function delete($id) {
    $this->db->query("DELETE FROM {$this->table} WHERE id=?d", $id);
  }
  
  public function updateField($id, $k, $v) {
    $this->db->query("UPDATE {$this->table} SET $k=? WHERE id=?d", $v, $id);
  }
  
  // ----------------------- getting items ------------------------------
  
  public function getItems() {
    $this->prepareItemsConds();
    $q = "
    SELECT
      {$this->table}.*,
      UNIX_TIMESTAMP({$this->table}.dateCreate) AS dateCreate_tStamp,
      UNIX_TIMESTAMP({$this->table}.dateUpdate) AS dateUpdate_tStamp,
      {$this->table}.id AS ARRAY_KEY
    FROM {$this->table}".$this->cond->all();
    return $this->db->query($q);
  }
  
  public function getItemIds() {
    $this->prepareItemsConds();
    return $this->db->ids($this->table, $this->cond);
  }
  
  public function getPagination() {
    if (!isset($this->itemsTotal))
      throw new NgnException('Use DbItems::prepareItemsConds() before');
    array(
      'itemsTotal' => $this->itemsTotal,
      'pagesTotal' => $this->pagesTotal,
      'pNums' => $this->pNums
    );
  }
  
  // ---------------------- items select conditions -----------------
  
  protected $selectCond;
  
  /**
   * Есть ли постраничная выборка 
   *
   * @var bool
   */
  public $isPagination = false;
  
  /**
   * Количество записей на одной странице
   * Используется только при постраничной выборке ($this->isPagination = true)
   *
   * @var integer
   */
  protected $n = 20;
  
  /**
   * HTML код со ссылками на страницы
   *
   * @var strgin
   */
  public $pNums;
  
  protected $itemsCondsPrepared = false;

  /**
   * Общее кол-во записей не учитывая страничные лимиты 
   *
   * @var integer
   */
  public $itemsTotal;

  /**
   * Общее кол-во записей не учитывая страничные лимиты 
   *
   * @var integer
   */
  public $pagesTotal;  

  protected function prepareItemsConds() {
    if ($this->itemsCondsPrepared) return;
    if ($this->isPagination) {
      list($this->pNums, $offset, $this->itemsTotal, $this->pagesTotal)
        = O::get('Pagination', array('n' => $this->n))->get($this->table, $this->cond);
      $this->cond->setLimit($offset);
    }
    $this->itemsCondsPrepared = true;
  }

  public function addSelectCond($cond) {
    $this->selectCond .= ", $cond\n";
  }
  
  // ----------------- static -------------------
 
  static public function createDummyTable($name) {
    db()->query("
CREATE TABLE IF NOT EXISTS `$name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT '1',
  `dateCreate` datetime DEFAULT NULL,
  `dateUpdate` datetime DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
");
  }
  
  public function addF($key, $value) {
    $this->cond->addF($key, $value);
    return $this;
  }
  
}