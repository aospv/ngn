<?php

class VkAuthBase {

  public $auth;

  public function __construct(VkAuth $auth) {
    Misc::checkEmpty($auth->authorized);
    $this->auth = $auth;
  }

}