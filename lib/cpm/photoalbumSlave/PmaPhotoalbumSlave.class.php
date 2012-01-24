<?php

class PmaPhotoalbumSlave extends Pma {

  public function clearMasterCache() {
    O::get('PhotoalbumCore', $this->options['masterStrName'])->
      deleteImages($this->options['masterItemId']);
  }

}