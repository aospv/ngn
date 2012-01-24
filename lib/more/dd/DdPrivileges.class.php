<?php

class DdPrivileges {
  
  function get($userId, $strName) {
    foreach (db()->select("
    SELECT userId, type, field
    FROM dd_privileges WHERE strName=?", $strName) as $v) {
      $_priv[$v['userId']][$v['type']][] = $v['field'];
    }
    // Добавляем к ним привилегии для конкретного пользователя, если они определены
    if ($_priv[$userId]) $priv = $_priv[$userId];
    // Записываем привилегии сначало для всех пользователей, если они определены
    if ($_priv[ALL_USERS_ID]) {
      if (!$priv) $priv = $_priv[ALL_USERS_ID];
      else foreach ($_priv[ALL_USERS_ID] as $action => $fields) {
        foreach ($fields as $field) {
          if (!in_array($field, $priv[$action])) $priv[$action][] = $field;
        }        
      }
    }
    return $priv;
  }
  
}
