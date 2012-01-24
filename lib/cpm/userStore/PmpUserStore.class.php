<?php

class PmpUserStore extends Pmp {

  protected function init() {
    if (DbModelCore::get('userStoreSettings', $this->userId) !== false)
      $this->r['create'] = true;
  }

}
