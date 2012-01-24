<?php

class DmfaImage extends DmfaFile {

  public function afterCreateUpdate(FieldEFile $el) {
    $path = parent::afterCreateUpdate($el);
    sys("convert $path -colorspace RGB $path");
    return $path;
  }

}