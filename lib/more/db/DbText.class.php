<?php

class DbText {
  
  static public function replaceFieldText($table, $field, $pattern, $replacer) {
    foreach (db()->select("SELECT id, $field FROM $table") as $v) {
      if (preg_match($pattern, $v[$field])) {
        $v[$field] = preg_replace($pattern, $replacer, $v[$field]);
        db()->query("UPDATE $table SET $field=? WHERE id=?d", $v[$field], $v['id']);
      }
    }
  }
  
  /**
   * Возвращает имена тектовых полей
   */
  static public function getTextNames($table) {
    $names = array();
    foreach (db()->query("SHOW COLUMNS FROM $table") as $y) {
      preg_match('/([a-z]+)(\(([0-9]+)\))*/', $y['Type'], $m);
      if (in_array($m[1], self::$textTypes)) {
        if (!$m[3] or ($m[3] and $m[3] > 15)) {
          $names[] = $y['Field'];
        }
      }
    }
    return $names;
  	
  }

}