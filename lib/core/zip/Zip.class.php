<?php

class Zip {
  
  protected static $obj;
  
  private static function getObj() {
    if (self::$obj) return self::$obj;
    if (0 and class_exists('ZipArchive')) {
      self::$obj = new ZipArchiveExtended();
    } elseif (getOS() == 'linux') {
      self::$obj = new ZipLinux();
    } else {
      self::$obj = new PclZipExtended();
    }
    return self::$obj;
  }
  
  /**
   * Создает архив с файлом или добавляет файл к существующему архиву
   *
   * @param   string  Путь к архиву
   * @param   string  Путь к файлу
   * @param   string  Путь в файловой структуре архиве. По умолчанию - корень
   */
  static public function file($archive, $file, $localpath = '') {
    if (is_file($archive)) unlink($archive);
    self::getObj()->file($archive, $file, $localpath);
  }
  
  /**
   * Создает архив с каталогом или добавляет каталог к существующему архиву
   *
   * @param   string  Путь к архиву
   * @param   string  Путь к каталогу
   * @param   string  Путь в файловой структуре архива. По умолчанию - корень
   */
  static public function dir($archive, $path, $localpath = null) {
    self::getObj()->dir($archive, $path, $localpath);
  }
  
  static public function extract($from, $to, $strict = true) {
    if (!file_exists($from)) throw new NgnException("File '$from' does not exists");
    return self::getObj()->extract($from, $to, $strict);
  }
  
  static public function lst($archive) {
    return self::getObj()->lst($archive);
  }
  
  static public function archive($tempFolder, $what, $archiveFilename, $whatRenamed = null) {
    File::checkExists($what);
    $what = realpath($what);
    $archive = $tempFolder.'/'.$archiveFilename;
    if (!$whatRenamed) $whatRenamed = basename($what);
    output("Archivate '".$what."' renamed to '$whatRenamed' to archive '$archive'");
    if (file_exists($archive)) unlink($archive);
    if (is_dir($what)) {
      Zip::dir($archive, $what, $whatRenamed);
    } else {
      Zip::file($archive, $what, $whatRenamed);
    }
    return $archive;
  }
  
  static public function add($archive, $what) {
    if (($what = realpath($what)) === false) throw new NoFileException($what);
    $whatRenamed = basename($what);
    if (is_dir($what)) {
      Zip::dir($archive, $what, $whatRenamed);
    } else {
      Zip::file($archive, $what, $whatRenamed);
    }
    return $archive;
  }
  
}
