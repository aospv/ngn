<?php

class SubsReturn {
  
  /**
   * Создаёт новую запись успешного возврата по ссылке из рассылки
   *
   * @param   string  Код
   * @param   string  users/emails
   */
  public function __construct($subsId, $code, $type, $url) {
    if (!in_array($type, array('users', 'emails')))
      throw new NgnException("\$_REQUEST['type'] must be 'users' or 'emails'");
    if ($type == 'users') {
      $email = db()->selectCell('SELECT email FROM users WHERE actCode=?', $code);
    } else {
      $email = db()->selectCell('SELECT email FROM subs_emails WHERE code=?', $code);
    }
    if (!$email)
      throw new NgnException("Email not found by type '$type'");
    if (db()->query('SELECT * FROM subs_returns WHERE subsId=?d AND email=?', $subsId, $email))
      return;
    db()->query('INSERT INTO subs_returns SET subsId=?d, email=?, type=?, returnDate=?',
      $subsId, $email, $type, dbCurTime());
  }
  
}
