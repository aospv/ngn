<?php

/**
 * - Создать бэкап-запись - копию имеющейся + данные о бэкапе
 *   - данные о бэкапе - это
 *     - дата создания бэкапа
 *     
 *   - .....................
 * - Если таблица для записи не существует - создать её
 *
 */
class DdBackup {
  
  static public $maxBackupItems = 3;
  
  static public $prefix = 'backup';
  
  private static function createTable($name) {
    return DdStructure::createTable(self::$prefix.'_'.$name);
  }

  private static function tableExists($name) {
    return tableExists('d?_'.self::$prefix.'_'.$name);
  }
  
  static public function save($strName, $pageId, $itemId) {
    // необходимо сравнить таблицы. если они отличаются, 
    // нужно удалить старую и создать новую по новой структуре 
    // в данном случае данные бэкапов будут утеряны, но по крайней мере мы избежим ошибки
    if (self::tableExists($strName) and
        !compareTables("d?_$strName", 'd?_'.self::$prefix.'_'.$strName)) {
      db()->query('DROP TABLE d?_'.self::$prefix.'_'.$strName);
    }
    if (!self::tableExists($strName)) {    
      // Копируем структуру таблицы
      copyTable("d?_$strName", 'd?_'.self::$prefix.'_'.$strName, false);    
      // Убираем автоинкремент
      db()->query(
        "ALTER TABLE d?_".self::$prefix."_$strName CHANGE id id INT(11) NOT NULL");
      // Удаляем уникальный индекс
      db()->query(
        "ALTER TABLE d?_".self::$prefix."_$strName DROP INDEX id");
      // Назначаем обычный индекс
      db()->query(
        "ALTER TABLE d?_".self::$prefix."_$strName ADD INDEX (id)");
      db()->query(
        "ALTER TABLE d?_".self::$prefix."_$strName ADD dateBackup DATETIME NOT NULL");
    }
    // Копируем запись
    $item = db()->selectRow(
      "SELECT * FROM d?_$strName WHERE pageId=?d AND id=?d",
      $pageId, $itemId);

    
    // Проверяем изменилась ли запись с момента прошлого сохранения, если нет - не сохраняем
    $lastItem = self::getLast($strName, $pageId, $itemId);
    unset($lastItem['dateBackup']);
    if (!array_diff_assoc(
      $lastItem,
      $item
    )) return;
    
    $item['dateBackup'] = dbCurTime();
    
    // Удаляем более старые записи
    foreach (db()->select("
      SELECT dateBackup FROM d?_".self::$prefix."_$strName
      WHERE pageId=?d AND id=?d ORDER BY dateBackup DESC",
      $pageId, $itemId) as $v) {
      $n++;
      if ($n > self::$maxBackupItems-1) {
        $tooOldDate = $v['dateBackup'];
        break;
      }
    }
    if ($tooOldDate) {
      db()->query(
        "DELETE FROM d?_".self::$prefix."_$strName WHERE dateBackup <= ?", $tooOldDate);
    }
    
    
    // Сохраняем запись
    db()->query(
      "INSERT INTO d?_".self::$prefix."_$strName SET ?a",
      $item
    ); 
    
  }
  
  static public function getLast($strName, $pageId, $itemId) {
    return db()->selectRow("
      SELECT * FROM d?_".self::$prefix."_$strName
      WHERE pageId=?d AND id=?d ORDER BY dateBackup DESC LIMIT 1",
      $pageId, $itemId);
  }
  
  static public function get($strName, $pageId, $itemId) {
    pr(self::$prefix);
    return db()->select("
      SELECT * FROM d?_".self::$prefix."_$strName
      WHERE pageId=?d AND id=?d ORDER BY dateBackup DESC",
      $pageId, $itemId);
  }
  
  static public function rollBack($strName, $pageId, $itemId, $dateBackup) {
    $item = db()->selectRow("
      SELECT * FROM d?_".self::$prefix."_$strName
      WHERE pageId=?d AND id=?d AND dateBackup=?",
      $pageId, $itemId, $dateBackup);
    unset($item['dateBackup']);
    db()->selectRow(
      "UPDATE d?_$strName SET ?a WHERE id=?d",
      $item, $item['id']);
  }

}

class DD_Autosave extends DdBackup {

  static public $prefix = 'autosave';
  
}