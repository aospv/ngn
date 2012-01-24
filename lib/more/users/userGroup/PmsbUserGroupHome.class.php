<?php

class PmsbUserGroupHome extends PmsbAbstract {

  public function initBlocks() {
    $this->addBlock(array(
      'colN' => 1,
      'type' => 'userGroupInfo',
      'html' => Tt::getTpl('pmsb/userGroup', $this->ctrl->userGroup
    )));
  }
}
