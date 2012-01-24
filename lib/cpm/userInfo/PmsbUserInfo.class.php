<?php

class PmsbUserInfo extends PmsbAuthorItems {

  public function initBlocks() {
    $this->addBlock(array(
      'colN' => 1,
      'type' => 'userInfo',
      'html' => Tt::getTpl('pmsb/userInfo', $this->ctrl->d['user']),
    ));
  }

}