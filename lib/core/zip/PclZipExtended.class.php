<?php

class PclZipExtended extends ZipExtended {
  
  public function file($archive, $file, $localpath = '') {
    if (!is_file($file))
      throw new NgnException("File '$file' does not exists");
    $oPclZip = new PclZip($archive);
    if (!$localpath)
      $list = $oPclZip->add($file, PCLZIP_OPT_REMOVE_ALL_PATH);
    else {
      $list = $oPclZip->add($file,
        PCLZIP_OPT_REMOVE_ALL_PATH,
        PCLZIP_OPT_ADD_PATH, dirname($localpath));
    }
    if ($list == 0)
      throw new NgnException($oPclZip->errorInfo(true));  
  }
  
  public function dir($archive, $path, $localpath = '') {
    if (!file_exists($path))
      throw new NgnException("Folder '$path' does not exists");
    $oPclZip = new PclZip($archive);    
    if (!$localpath)
      $list = $oPclZip->add($path,
        PCLZIP_OPT_REMOVE_PATH, dirname($path));
    else {
      $list = $oPclZip->add($path,
        PCLZIP_OPT_REMOVE_PATH, $path,
        PCLZIP_OPT_ADD_PATH, $localpath);
    }
    if ($list == 0)
      throw new NgnException($oPclZip->errorInfo(true));
  }
  
  public function lst($archive) {
    $oPclZip = new PclZip($archive);
    return $oPclZip->listContent();    
  }
  
  protected function _extract($from, $to, $strict = true) {
    $oPclZip = new PclZip($from);
    $r = ($oPclZip->extract(PCLZIP_OPT_PATH, $to) != 0);
    if (!$r and $strict) {
      throw new NgnException($oPclZip->errorInfo(true));
    }
    return $r;
  }
  
}