<?php

class PcsaPhotoalbum extends PcsaItemsMaster {

  public function action($initSettings) {
    O::get('PhotoalbumCore', $this->page['strName'])->cleanup();
    return parent::action($initSettings);
  }

}
