<?php

class VkFriends extends VkAuthBase {

  public function storeFriends() {
    $ids = array();
    for ($i=1; $i<=12; $i++) {
      $c = $this->auth->get("http://m.vkontakte.ru/friends$i?&all=1");
      preg_match_all('/href="\/id(\d+)"/', $c, $m);
      $ids = array_merge($ids, $m[1]);
    }
    FileList::replace($this->auth->userDataFolder.'/friends', $ids);
  }
  
  public function getFriends() {
    if (!file_exists($this->auth->userDataFolder.'/friends')) {
      $this->storeFriends();
    }
    return FileList::get($this->auth->userDataFolder.'/friends');
  }

}