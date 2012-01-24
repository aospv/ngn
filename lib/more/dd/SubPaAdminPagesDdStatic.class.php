<?php

class SubPaAdminPagesDdStatic extends SubPaAdminPagesDd {

  public function init() {
    parent::init();
    if (!isset($this->oPA->oReq->r['a'])) $this->oPA->redirect(Tt::getPath().'?a=edit');
  }

}