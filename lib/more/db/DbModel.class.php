<?php

class DbModel extends NgnArrayAccess {

  static public $serializeble = array();
  
  static public $hasAutoIncrement = true;
  
  static public $hasDefaultDateFields = true;
  
  protected $table;
  
  public $fromCache = false;
  
  public function __construct($table, $value, $param = 'id') {
    Misc::checkEmpty($table);
    $this->table = $table;
    if ($param != 'id') {
      $id = DbModelCore::getIdByParam($table, $param, $value);
    } else {
      $id = $value;
    }
    if (!DbModelCore::$forceCache and $id !== false) {
      if (($this->r = Mem::get(DbModelCore::cacheId($table, $id))) !== false) {
        $this->fromCache = true;
        return;
      }
    }
    $this->r = db()->selectRow("SELECT * FROM $table WHERE $param=?", $value);
    if (!empty($this->r)) {
      Arr::checkEmpty($this->r, 'id');
      static::unpack($this->r);
      Mem::set(DbModelCore::cacheId($table, $this->r['id']), $this->r);
      if ($param != 'id') {
        DbModelCore::saveIdByParam($table, $param, $value, $this->r['id']);
      }
    }
  }
  
  public function save() {
    DbModelCore::update($this->table, $this->r['id'], $this->r);
  }
  
  static public function unpack(array &$r) {
    foreach (static::$serializeble as $k) $r[$k] = unserialize($r[$k]);
  }
  
  static public function pack(array &$data) {
    if (empty(static::$serializeble)) return;
    foreach (static::$serializeble as $name)
      if (isset($data[$name])) $data[$name] = serialize($data[$name]);
  }
  
  static public function addDefaultUpdateData(array &$data) {
    if (!static::$hasDefaultDateFields) return;
    if (empty($data['dateUpdate'])) $data['dateUpdate'] = dbCurTime();
  }
  
  static public function update($table, $id, array $data, $filterByFields = false) {
    self::pack($data);
    self::addDefaultUpdateData($data);
    if ($filterByFields)
      $data = Arr::filter_by_keys($data, db()->cols($table));
    db()->query("UPDATE $table SET ?a WHERE id=?", $data, $id);
    DbModelCore::cc($table, $id);
  }
  
  static public function addDefaultCreateData(array &$data) {
    if (!static::$hasDefaultDateFields) return;
    if (empty($data['dateCreate'])) $data['dateCreate'] = dbCurTime();
    if (empty($data['dateUpdate'])) $data['dateUpdate'] = dbCurTime();
  }
  
  static public function create($table, array $data, $filterByFields = false) {
    self::pack($data);
    self::addDefaultCreateData($data);
    Misc::checkEmpty($data);
    if ($filterByFields)
      $data = Arr::filter_by_keys($data, db()->cols($table));
    return db()->query("INSERT INTO $table SET ?a", $data);
  }

}