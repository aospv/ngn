<?php

class PbvAuth extends PbvAbstract {

  public function _html() {
    return Tt::getTpl('auth/login');
  }

}
