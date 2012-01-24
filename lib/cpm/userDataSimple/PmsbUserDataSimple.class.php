<?php

class PmsbUserDataSimple extends PmsbAbstract {

  public function initBlocks() {
    $this->blocks[] = array(
      'colN' => 1,
      'html' => Tt::getTpl('pmsb/userInfo', $this->ctrl->d['user'])
    );
  }

}