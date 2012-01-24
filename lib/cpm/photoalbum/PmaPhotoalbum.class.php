<?php

class PmaPhotoalbum extends Pma {

  public function delete() {
    O::get('PhotoalbumCore', $this->options['oItems']->strName)->
      deleteImages($this->options['id']);
  }

}