<?php

class DbModelCore {

  static public $forceCache = true;
  
	/**
   * @return DbModel
   */
  static public function get($table, $value, $param = 'id') {
    if ($param == 'id' and !$value) return false;
    $class = self::getClass($table);
    $o = new $class($table, $value, $param);
    return empty($o->r) ? false : $o;
  }
  
  static public function take($table, $value, $param = 'id') {
    if (($r = self::get($table, $value, $param)) === false) throw new NgnException('No such model');
    return $r;
  }
  
  static public function getClass($table) {
    $class = 'DbModel'.ucfirst($table);
    return O::exists($class) ? $class : 'DbModel';
  }
  
  static public $defaultCreateValues = array();
  
  static public function create($table, array $data, $filterByFields = false) {
    $class = self::getClass($table);
    if ($class == 'DbModel')
      return $class::create($table, $data);
    else {
      if (!empty($class::$defaultCreateValues)) {
        foreach ($class::$defaultCreateValues as $k => $v) {
          if (!isset($data[$k])) $data[$k] = $v; 
        }
      }
      if (method_exists($class, 'beforeCreateUpdate')) $class::beforeCreateUpdate($data);
      if (method_exists($class, '_create'))
        $id = $class::_create($data, $filterByFields);
      else {
        $id = $class::create($table, $data, $filterByFields);
      }
      if (method_exists($class, 'afterCreateUpdate')) $class::afterCreateUpdate($id);
      return $id;
    }
  }
  
  static public function update($table, $id, array $data, $filterByFields = false) {
    $class = self::getClass($table);
    if ($class == 'DbModel')
      $class::update($table, $id, $data);
    else {
      if (method_exists($class, 'beforeCreateUpdate')) $class::beforeCreateUpdate($data);
      if (method_exists($class, '_update'))
        $class::_update($id, $data, $filterByFields);
      else
        $class::update($table, $id, $data, $filterByFields);
      if (method_exists($class, 'afterCreateUpdate')) $class::afterCreateUpdate($id);
    }
  }
  
  static public $replaceCreate;
  
  static public function replace($table, $id, array $data, $filterByFields = false) {
    if (self::get($table, $id)) {
      self::update($table, $id, $data, $filterByFields);
      self::$replaceCreate = false;
    } else {
      if (!ClassCore::getStaticProperty(self::getClass($table), 'hasAutoIncrement')) {
        $data['id'] = $id;
        self::create($table, $data, $filterByFields);
      } else {
        $id = self::create($table, $data, $filterByFields);
      }
      self::$replaceCreate = true;
    }
    return $id;
  }
  
  static public function delete($table, $id) {
    db()->query("DELETE FROM $table WHERE id=?", $id);
    self::cc($table, $id);
  }
  
  static public function deleteByCond($table, DbCond $cond) {
    foreach (db()->selectCol('SELECT id FROM '.$table.$cond->all()) as $id)
      self::delete($table, $id);
  }
  
  static public function cacheId($table, $id) {
    return 'model'.$table.$id;
  }
  
  static public function cc($table, $id) {
    $class = self::getClass($table);
    if (($ids = Memc::get('modelids'.$table)) !== false) {
      foreach ($ids as $k => $_id) {
        if ($_id == $id) {
          preg_match('/(\w)\/(.*)/', $k, $m);
          //                value  param
          O::delete($class, $m[2], $m[1]);
        }
      }
    }
    O::delete($class, $id, 'id');
    Mem::delete(self::cacheId($table, $id));
  }
  
  static $ids = array();
  
  static public function getIdByParam($table, $param, $value) {
    if (!isset(self::$ids[$table]))
      self::$ids[$table] = Memc::get('modelids'.$table);
    return isset(self::$ids[$table][$param.'/'.$value]) ?
      self::$ids[$table][$param.'/'.$value] : false;
  }
  
  static public function saveIdByParam($table, $param, $value, $id) {
    $ids = Memc::get('modelids'.$table);
    $ids[$param.'/'.$value] = $id;
    Memc::set('modelids'.$table, $ids);
    self::$ids[$table] = $ids[$param.'/'.$value];
  }
  
  const modeArray = 1;
  const modeObject = 2;
  
  /**
   * @param string  Query after FROM keyword
   * @param array   args
   * @param integer Mode
   * ... 
   */
  static public function collection($table, DbCond $cond = null, $mode = self::modeArray) {
    $args = func_get_args();
    $cond = $cond ? $cond->all() : '';
    if ($mode == self::modeArray) {
      $items = db()->select("SELECT * FROM ".$table.$cond);
      $class = self::getClass($table);
      foreach ($items as $k => &$item) $class::unpack($item);
      return $items;
    } else {
      $ids = db()->selectCol("SELECT id FROM ".$table.$cond);
      $r = array();
      foreach ($ids as $id) $r[] = self::get($table, $id);
      return $r;
    }
  }
  
  static public function count($table, DbCond $cond = null) {
    $cond = $cond ? $cond->all() : '';
    $key = sha1($table.$cond);
    //if (($cnt = Mem::get($key)) !== false) return $cnt;
    $cnt = db()->selectCell("SELECT COUNT(*) FROM ".$table.$cond);
    //Mem::set($key, $cnt);
    return $cnt;
  }
  
  static public function pagination($n, $table, DbCond $cond = null, $mode = self::modeArray) {
    list($pNums, $limit) = O::get('Pagination', array('n' => 30))->get($table, $cond);
    if (!$cond) $cond = DbCond::get();
    return array(
      'items' => DbModelCore::collection('users', $cond->setLimit($limit)),
      'pagination' => array(
        'pNums' => $pNums
      )
    );
  }

}

