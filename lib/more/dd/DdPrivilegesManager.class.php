<?php

class DdPrivilegesManager {
  
  public function getFields() {
    $oStructure = new DdStructure();
    foreach ($oStructure->getStructures() as $v) {
      $fields[$v['name']]['strName'] = $v['name'];
      $oFields = O::get('DdFields', $v['name']);
      foreach ($oFields->getPrivFields() as $v2) {
        $fields[$v['name']]['fields'][] = $v2['name'];
      }       
    }
    return $fields;
  }
  
  /**
   * ПОСЛЕ ВВЕДЕНИЯ ТИПА (type) НУЖНО ПЕРЕДЕЛАТЬ
   *
   * @param unknown_type $userId
   * @return unknown
   */
  public function getByUser($userId) {
    $privs = array();
    foreach (db()->select("
    SELECT pageId, strName, field FROM dd_privileges WHERE userId=?d", $userId) as $k => $v) {
      $privs[$v['strName']][] = $v['field'];
    }
    if (!$privs) return false;
    return $privs;
  }
  
  public function getActions() {
    $actions = array('create', 'edit');
    if (!$fields = $this->getFields()) return false;
    foreach ($actions as $action) {
      foreach ($fields as $field) {
        $actionTypes[$action][] = $field;
      }
    }
    return $actionTypes;
  }
  
  public function decribe(&$privs) {
    foreach ($privs as $type => $v) $types[] = "'$type'";
    $pages = db()->selectCol("SELECT id AS ARRAY_KEY, title FROM pages
    WHERE id IN (".implode(',', $types).")");
    if (!$pages) return;
    foreach ($pages as $id => $title) {
      $privs[$id]['title'] = $title;
    }
  }
  
  /**
   * Добавляет привилегию "структура - пользователь"
   *
   * @param   integer   ID пользователя
   * @param   integer   ID страницы
   * @param   string    Привилегия (Например: 'edit')
   */
  static function create($userId, $strName, $type, $field) {
    db()->query(
    "INSERT INTO dd_privileges SET userId=?d, strName=?, type=?, field=?",
    $userId, $strName, $type, $field);
  }
  
  /**
   * Добавляет привилегии "раздел - пользователь", удаляя перед этим 
   * все привилегии для этого пользователя на этом разделе
   *
   * @param   integer   ID пользователя
   * @param   array     Привилегии
   *                    Например:
   *                    array('subjects' => array(
   *                      'edit'
   *                    )
   */
  static function addPrivs_array($userId, $privs) {
    db()->query("DELETE FROM dd_privileges WHERE userId=?", $userId);
    if (!$privs) return;
    foreach ($privs as $strName => $privs2) {
      self::addPrivs_common($userId, $strName, $privs2);
    }
  }
  
  static function addPrivs_common($userId, $strName, $privs) {
    // Временная затычка 'edit'
    foreach ($privs as $k => $v) $privs[$k] = array('edit', 'create');
    foreach ($privs as $field => $types) {
      foreach ($types as $type) {
        self::create($userId, $strName, $type, $field);
      }
    }
  }
  
  static function addPrivs_strName($userId, $strName) {
    $field = O::get('DdFields', $strName);
    foreach ($field->getPrivFields() as $k => $v) {
      self::addPrivs_common($userId, $strName, array(
        $v['name'] => array('edit', 'create') // затычка
      ));
    }
  }

}