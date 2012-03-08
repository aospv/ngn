<?php

class PcsaPhotoalbum extends PcsaItemsMaster {

  public function action(array $initSettings) {
    O::get('PhotoalbumCore', $this->page['strName'])->cleanup();
    return parent::action($initSettings);
  }

}
