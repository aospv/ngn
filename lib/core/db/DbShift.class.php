<?php

class DbShift {
  
  /**
   * Перемещает запись таблицы, используя OID
   *
   * @param integer Уникальный ID записи в таблице с именем поля 'id'
   * @param string  Действие 'up', 'down'
   * @param string  Имя таблицы
   * @param string  Дополнительное условие запроса.
   *                Массив формата: array('k' => 'key', 'v' => 'value')
   */
  static public function item($id, $action, $table, $filters = null, $idName = 'id') {
    if (!$id = (int)$id) return;
    if ($action != 'up') $action = 'down';
    foreach ($filters as $k => $v) {
      if (is_string($v)) $v = "'".mysql_escape_string($v)."'";
      $fltr .= " AND $k=$v"; 
    }
    $moveIds = db()->selectCol("
               SELECT $idName FROM $table WHERE
                 1
                 $fltr
               ORDER BY oid");
    for ($i=0; $i<count($moveIds); $i++) {
      if ($moveIds[$i] == $id) {
        if ($action == 'up') {
          if (!$moveIds[$i-1]) continue;
          $curId = $moveIds[$i-1];
          $moveIds[$i-1] = $id;
          $moveIds[$i] = $curId;
        } else {
          if (!$moveIds[$i+1]) continue;
          $curId = $moveIds[$i+1];
          $moveIds[$i+1] = $id;
          $moveIds[$i] = $curId;
        }
        break;
      }
    }
    DbShift::items($moveIds, $table, $idName);
  }
  
  static public function items(array $moveIds, $table, $idName = 'id') {
    for ($i=0; $i<count($moveIds); $i++) {
      db()->query(
        "UPDATE $table SET oid=?d WHERE $idName=?d",
        ($i+1)*10, $moveIds[$i]);
    }
  }
  
  static public function sort($table, $filter = null) {
    $ids = db()->selectCol("
      SELECT id FROM $table WHERE 1
      {AND ".(empty($filter) ? '' : $filter['k'])." = ?}
      ORDER BY oid",
      (empty($filter) ? DBSIMPLE_SKIP : $filter['v']));
    for ($i=0; $i<count($ids); $i++) {
      db()->query("UPDATE $table SET oid=?d WHERE id=?d",
        ($i+1)*10, $ids[$i]);
    }
  }
  
  static public function sort2($table, $filters = null) {
    if ($filters) {
      foreach ($filters as $k => $v) {
        $f[] = "$k='".mysql_escape_string($v)."'";
      }
      $f = 'WHERE '.implode(' AND ', $f);
    }
    $ids = db()->selectCol("SELECT id FROM $table $f ORDER BY oid");
    for ($i=0; $i<count($ids); $i++) {
      db()->query("UPDATE $table SET oid=?d WHERE id=?d",
        ($i+1)*10, $ids[$i]);
    }
  }

}
