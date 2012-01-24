<?php

class DdTextReformater {
  
  static private function _copy($format) {
    $oFormatText = new FormatText();
    $oFormatText->allowedTagsConfigName = 'comments.allowedTags';
    foreach (db()->selectCol('SELECT name FROM dd_structures') as $strName) {
      $fieldNames = db()->selectCol(
        "SELECT name FROM dd_fields WHERE strName=? AND type='textarea'", $strName);
      if (!$fieldNames)
        continue;
      $q = 'SELECT id, '.implode(', ', $fieldNames).' '.
        'FROM dd_i_'.$strName.' ORDER BY id DESC';
      $r = @mysql_query($q);
      if ($r === false) Err::sqlDie(mysql_error());
      while (($row = mysql_fetch_assoc($r))) {
        $a = array();
        foreach ($fieldNames as $fieldName) {
          $a[$fieldName] = $row[$fieldName];
          $a[$fieldName.'_f'] = $format ? $oFormatText->html($row[$fieldName]) : nl2br($row[$fieldName]);
        }
        try {
          db()->query("UPDATE dd_i_$strName SET ?a WHERE id=?d", $a, $row['id']);
        } catch (Exception $e) { }
      }
    }
  }
  
  /**
   * Вставляет текст из полей БД типа "textarea" в поля с именами name_f 
   */
  static public function copy() {
    self::_copy(false);
  }
  
  /**
   * Вставляет текст из полей БД типа "textarea" в поля с именами name_f,
   * форматирую при этом HTML 
   */
  static public function reformat() {
    self::_copy(true);
  }
  
}
