<?php

class DdoFieldTypeUsersChatLog {

  static public $dddd = '`<h3>`.$title.`</h3>`.DdoFieldTypeUsersChatLog::html($v)';
  
  static public function html($v) {
    foreach (explode("\n", $v) as $line) {
      preg_match('/([^:]+): (.*)/', $line, $m);
      $user = DbModelCore::get('users', $m[1]);
      $r[] = '<div class="item">'.
        UsersCore::avatar($user['id'], $user['login']).
        '<div class="text" style="float:left; width: 450px;">'.$m[2].'</div><div class="clear"></div></div>';
    }
    return '<div class="items usersChatLog">'.implode("\n", $r).'</div>';
  }

}