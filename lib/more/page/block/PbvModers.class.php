<?php

class PbvModers extends PbvAbstract {

  public function _html() {
    return Tt::getTpl(
      'pageBlocks/moders',
      O::get('Privileges')->getUsers($this->oPBM['settings']['pageId'], 'edit')
    );
  }

}
