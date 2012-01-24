<?php

class ZipArchiveExtended extends ZipExtended {

  /**
   * @var ZipArchive
   */
  private $oZipArchive;

  private $archive;

  public function __construct() {
    $this->oZipArchive = new ZipArchive();
  }

  /**
   * Добавляет в архив директорию
   *
   * @param   string  Путь к файлу архива
   * @param   string  Путь к директории
   * @param   string  Путь к директории в архиве
   */
  public function dir($archive, $path, $localpath = null) {
    $path = Misc::clearLastSlash($path);
    $this->archive = $archive;
    $this->oZipArchive->open($archive, ZIPARCHIVE::CREATE);
    if (!file_exists($path))
      throw new NgnException("Folder '$path' is not exists");
    if (!$localpath)
      $localpath = basename($path);
    $this->addDir($path, $localpath);
    $this->oZipArchive->close();
  }

  private function addDir($path, $localpath) {
    $this->oZipArchive->addEmptyDir($localpath);
    $nodes = Dir::get($path);
    if ($localpath)
      $localpath = $localpath . '/';
    foreach ($nodes as $v) {
      $node = $path . '/' . $v;
      $localpath2 = $localpath . basename($node);
      //if (!file_exists($node))        throw new NgnException("File '$node' not exists");
      if (is_dir($node)) {
        $this->addDir($node, $localpath2);
      } elseif (is_file($node)) {
        if ($this->oZipArchive->numFiles % 10 == 0) {
          $this->oZipArchive->close();
          $this->oZipArchive->open($this->archive, 
            ZIPARCHIVE::CREATE);
        }
        if ($this->oZipArchive->addFile($node, $localpath2) === false) {
          Err::warning('Add file "' . $node . '" to archive at path "'.$localpath2.'" error. '.
                  "Memry problem? Reduce portion of files number\n");
          return;
        }
      } else {
        throw new NgnException("'$node' is not a file or dir");
      }
    }
  }

  public function file($archive, $file, $localpath = '') {
    if (! is_file($file))
      throw new NgnException("File '$file' does not exists");
    $this->oZipArchive->open($archive, ZIPARCHIVE::CREATE);
    if (! $localpath)
      $localpath = basename($file);
    if ($this->oZipArchive->addFile($file, $localpath) === false)
      throw new NgnException(
        'Add file "' . $file . '" with localpath "' . $localpath . '" to archive error');
    $this->oZipArchive->close();
  }
  
  protected function _extract($from, $to) {
    $this->oZipArchive->open($from);
    $this->oZipArchive->extractTo($to);
    $this->oZipArchive->close();
  }
  
  public function lst($archive) {
    // dummy
  }

}