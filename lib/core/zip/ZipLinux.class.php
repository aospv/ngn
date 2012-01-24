<?php

class ZipLinux extends ZipExtended {

  public function dir($archive, $path, $localpath = null) {
    sys("zip -r $archive $path");
  }
  
  public function file($archive, $file, $localpath = null) {
    sys("zip $archive $file");
  }
  
  public function lst($archive) {
  }
  
  protected function _extract($from, $to) {
    sys("unzip $from -d$to");
  }

}
