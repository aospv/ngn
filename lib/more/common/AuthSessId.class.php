<?php

  
class AuthSessId {

  static function getData($sessionId) {
    $data = Session::read($sessionId);
    if (empty($data)) return false;
    $vars = preg_split(
      '/([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\|/',
      $data, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
    );
    for ($i=0; $vars[$i]; $i++)
      $result[$vars[$i++]] = unserialize($vars[$i]);
    return $result;
  }

  static public function auth($sessionId) {
    if (($data = self::getData($sessionId)) === false) return false;
    return Auth::login($data['auth']['login'], $data['auth']['pass']);
  }

}
