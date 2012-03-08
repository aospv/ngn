<?php

class EmailAdmin {

  static public function send($subject, $message, $html = true) {
    $o = new SendEmail();
    foreach (Config::getVar('admins') as $id) {
      $user = DbModelCore::get('users', $id);
      $o->send($user['email'], $subject, $message, $html);
    }
  }
	
}