<?php

class PmsbUserGroup extends PmsbAbstract {

  protected function initBlocks() {
    $this->addBlock(array(
      'colN' => 1,
      'html' => Tt::getTpl('pmsb/userGroup', $this->ctrl->userGroup),
    ));

  }

}