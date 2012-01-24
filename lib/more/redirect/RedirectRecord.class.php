<?php

class RedirectRecord {
  
  static public function save($type, $url) {
    if (!in_array($type, RedirectType::getTypes()))
      throw new NgnException('Type "'.$type.'" does not exists');
    if (db()->query('SELECT * FROM redirects WHERE url=? AND ip=?',
    $url, $_SERVER['REMOTE_ADDR'])) {
      return false;
    }
    db()->query('INSERT INTO redirects SET type=?, url=?, ip=?, userId=?d',
      $type, $url, $type, $_SERVER['REMOTE_ADDR'], Auth::get('id'));
    return true;
  }
  
}
