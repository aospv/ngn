<?php

class DmfaImage extends DmfaFile {

  protected function getExt(FieldEFile $el) {
    return 'jpg';
  }
  
  public function afterCreateUpdate(FieldEFile $el) {
    if (($path = parent::afterCreateUpdate($el)) !== false and File::getMime($path) == 'image/jpeg')
      sys("convert $path -colorspace RGB $path");
    return $path;
  }

}