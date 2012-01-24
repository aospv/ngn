<?php

class PbvugItems extends Pbvug {

  protected function init() {
    $this->pbv->oItems->cond->addF('userGroupId', $this->pbv->oCC->userGroup['id']);
  }

}
