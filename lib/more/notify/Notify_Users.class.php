<?php

class Notify_Users {
  
  function getUsers() {
    foreach (db()->select(
      "SELECT * FROM notify_subscribe_types") as $k => $v) {
      $users[] = $v;
    }
    return $users;
  }
  
  function getUsers_types() {
  }
  
  function getUsers_items() {
  }
  
}