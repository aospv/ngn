<?php

class VkSite {

  static protected $auth;

  /**
   * @return VkAuth
   */
  static public function getAuth() {
    if (isset(self::$auth)) return self::$auth;
    $conf = Config::getVar('vkAuth');
    self::$auth = new VkAuth($conf['login'], $conf['pass']);
    return self::$auth->auth();
  }
  
  /**
   * @return VkFriends
   */
  static public function friends() {
    return O::get('VkFriends', self::getAuth());
  }
    
  /**
   * @return VkMsgs
   */
  static public function msgs() {
    return O::get('VkMsgs', self::getAuth());
  }
  
  /**
   * @return VkUserInfo
   */
  static public function userInfo() {
    return O::get('VkUserInfo', self::getAuth());
  }
  
}