<?php

class PageBlockView_moders extends PageBlockViewAbstract {

  public function html() {
    return Tt::getTpl(
      'pageBlocks/moders',
      O::get('Privileges')->getUsers($this->oPBM['settings']['pageId'], 'edit')
    );
  }

}
