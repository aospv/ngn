<?php

class Dir {

  static public function make($path) {
    if (!strstr($path, '/') and !strstr($path, '\\'))
      Err::warning('Use absolute dir path. $path='.$path);
    if (!$existsPath = self::getExists($path))
      throw new NgnException("Can not detect existing parent path by '$path'. Not writable?");
    $extraPath = substr($path, strlen($existsPath), strlen($path));
    $folders = explode('/', Misc::clearLastSlash($extraPath));
    foreach ($folders as $folder) {
      $existsPath .= '/'.$folder;
      if (!is_dir($existsPath))
        self::makeDir($existsPath);
    }
    return $existsPath;
  }

  static private function makeDir($path) {
    mkdir($path) or Err::error("Making '$path'");
    chmod($path, 0777);
  }

  /**
   * Возвращает существующий путь к директории проходя вверх по данной директории
   *
   * @param   string  Путь к предполагаемой существующей директории
   * @return  mixed   false если таковой директории нет, и путь до директории, 
   *                  если она найдена
   */
  static public function getExists($_path) {
    $path = $_path;
    while (! is_dir($path) and $path != '/')
      $path = dirname($path);
    if (! is_writable($path))
      return false;
    return $path == '.' ? false : $path;
  }

  static public function isEmpty($path) {
    if (! is_dir($path))
      return false;
    $d = dir($path);
    while (false !== ($entry = $d->read())) {
      if ($entry != '.' and $entry != '..') {
        return false;
      }
    }
    $d->close();
    return true;
  }
  
  static public function removeIfEmpty($dirname) {
    if (!file_exists($dirname)) return;
    if (!glob($dirname.'/*')) rmdir($dirname);
  }

  /**
   * Рекурсивно удаляет директорию
   *
   * @param   string    Путь до папки
   */
  static public function remove($dirname, $removeItself = true) {
    // Sanity check
    if (!file_exists($dirname))
      return false;
      // Simple delete for a file or link
    if (is_file($dirname) or is_link($dirname)) {
      return unlink($dirname);
    }
    // Loop through the folder
    if (! $dir = dir($dirname))
      throw new NgnException("Check permissions for $dirname", true);
    while (false !== $entry = $dir->read()) {
      // Skip pointers
      if ($entry == '.' or $entry == '..') {
        continue;
      }
      // Recurse
      self::remove("$dirname/$entry");
    }
    // Clean up
    $dir->close();
    if ($removeItself)
      rmdir($dirname);
  }

  /**
   * Удаляет содержимое директории
   *
   */
  static public function clear($dirname) {
    self::remove($dirname, false);
  }

  static public function getFiles_noCache($dirPath, $quietly = false) {
    if (!file_exists($dirPath)) {
      if ($quietly) return array();
      else throw new NgnException("Folder '$dirPath' not exists");
    }
    $files = array();
    $d = dir($dirPath);
    while (($entry = $d->read())) {
      if (is_file($dirPath.'/'.$entry)) {
        $files[] = $entry;
      }
    }
    $d->close();
    sort($files);
    return $files;
  }

  static $nnn = 0;

  private static $pattern;

  static public function getFilesR($dirPath, $pattern = '*') {
    $pattern = str_replace('.', '\\.', $pattern);
    $pattern = str_replace('*', '.*', $pattern);
    $pattern = str_replace('/', '\/', $pattern);
    self::$pattern = $pattern;
    return self::_getFilesR($dirPath);
  }
  
  static public function getFlat($dirPath) {
    $r = glob($dirPath.'/*');
    $files = array();
    foreach ($r as $entry) {
      if ($entry == '.' or $entry == '..') continue;
      $files[] = $entry;
    }
    return $files;
  }
  
  /**
   * Удаляет файлы по маске
   *
   * @param   string  Путь к каталогу
   * @param   string  Маска
   */
  static public function deleteFiles($dirPath, $pattern) {
  	foreach (self::getFilesR($dirPath, $pattern) as $file) unlink($file);
  }
  
  static protected $includingDirs = false;
  
  static private function _getFilesR($dirPath) {
    $dirPath = Misc::clearLastSlash($dirPath);
    $files = array();
    if (($r = glob($dirPath . '/*')) === false) return $files;
    foreach ($r as $entry) {
      if ($entry == '.' or $entry == '..') continue;
      if (is_file($entry)) {
        if (self::$pattern == '*' or preg_match('/^'.self::$pattern.'$/', $entry)) {
          $files[] = $entry;
        }
      } elseif (is_dir($entry)) {
        $files = Arr::append($files, self::_getFilesR($entry));
      }
    }
    return $files;
  }

  static public function get($dirPath) {
    if (! file_exists($dirPath))
      throw new NgnException("Folder '$dirPath' does not exists");
    $dirs = array();
    $d = dir($dirPath);
    while (($entry = $d->read()) !== false) {
      if ($entry == '.' or $entry == '..') {
        continue;
      }
      $dirs[] = $entry;
    }
    $d->close();
    return $dirs;
  }

  static public function dirs($dirPath) {
    $dirs = array();
    if (!file_exists($dirPath)) return array();
    if (!($d = dir($dirPath)))
      throw new NgnException("Can't open dir \"$dirPath\"");
    while (($entry = $d->read()) !== false) {
      if ($entry == '.' or $entry == '..') {
        continue;
      }
      if (is_dir($dirPath.'/'.$entry)) {
        $dirs[] = $entry;
      }
    }
    $d->close();
    sort($dirs);
    return $dirs;
  }
  
  /*
  static $_dirs;
  
  static public function dirsR($dirPath) {
    self::$_dirs = array();
    return self::_dirsR($dirPath);
  }
  
  static public function _dirsR($dirPath) {
    foreach (glob($dirPath.'/*') as $v) {
      self::$_dirs[] = $v;
      if (is_dir($v));
    }
  }
  */
  
  static public function dirsDetail($dirPath) {
    $_dirs = array();
    foreach (self::dirs($dirPath) as $dir) {
      $files = self::files($dirPath.'/'.$dir);
      $size = 0;
      foreach ($files as $file)
        $size += filesize($dirPath.'/'.$dir.'/'.$file);
      $_dirs[] = array(
        'path' => $dirPath.'/'.$dir,
        'name' => $dir,
        'files' => count($files),
        'size' => $size
      );
    }
    return $_dirs;
  }

  static public function isDirs($dirPath) {
    $d = dir($dirPath);
    while (($entry = $d->read())) {
      if ($entry == '.' or $entry == '..') {
        continue;
      }
      if (is_dir($dirPath . '/' . $entry)) {
        $d->close();
        return true;
      }
    }
    $d->close();
    return false;
  }

  static public function files($dirPath, $quietly = false) {
    return self::getFiles_noCache($dirPath, $quietly);
  }

  static public function everyNFile($dirPath, $from, $n) {
    $entrys = self::files($dirPath);
    sort($entrys);
    $filesStep = round((count($entrys) - $from) / $n);
    if ($filesStep > 1) {
      $firstI = $from;
      $i = $firstI;
      while ($i < $firstI + ($filesStep * $n)) {
        $files[] = $entrys[$i];
        $i += $filesStep;
      }
    }
    return $files;
  }

  static public function getFiles($dirPath, $quietly = false) {
    if (IS_MEMCACHED === true) {
      if (($files = DirMem::get(str_replace('/', '_', $dirPath))))
        return $files;
      $files = self::getFiles_noCache($dirPath, $quietly);
      DirMem::set(str_replace('/', '_', $dirPath), $files, 60);
      return $files;
    }
    return self::getFiles_noCache($dirPath, $quietly);
  }

  static public function copy_($soursePath, $destPath) {
    if (! is_dir($soursePath))
      throw new NgnException("Sourse folder '$destPath' not exists");
    if (! is_dir($destPath))
      throw new NgnException("Destination folder '$destPath' not exists");
    if (getOS() == 'unix1') {
      system("cp -r $soursePath $destPath", $error);
      if ($error)
        throw new NgnException(
          "Can't copy dir '$soursePath' to '$destPath' " . "(check permissions for $destPath)");
    } else {
      self::copyPhp($soursePath, $destPath);
    }
  }

  static public function copy($dir1, $dir2, $replace = true) {
    if ($replace and file_exists($dir2)) self::remove($dir2);
    if (!file_exists($dir2)) self::make($dir2);
    self::copyPhpContents($dir1, $dir2);
  }

  static public $nonCopyNames = array(
    '.svn'
  );

  static public $replaceExistsFolders = true;

  static public function copyPhpContents($dir1, $dir2) {
    Misc::clearLastSlash($dir2);
    if (!is_dir($dir2))
      throw new NgnException("Dir '$dir2' not exists", true);
    if (($dh = opendir($dir1)) !== false) {
      $i = 0;
      while (($el = readdir($dh)) !== false) {
        // Если имя каталога-файла в числе тех, что не нужно копировать
        if (in_array($el, self::$nonCopyNames)) continue;
        $path1 = $dir1.'/'.$el;
        $path2 = $dir2.'/'.$el;
        if (strstr($dir2, $path1)) continue; // нельзя копировать ту папку, в которую копируем
        if (is_dir($path1) && $el != '.' && $el != '..') {
          if (!self::$replaceExistsFolders and is_dir($path2)) continue;
          if (!mkdir($path2)) throw new NgnException("Cant make '$path2'. Already exists");
          //chmod($path2, substr(sprintf('%o', fileperms($path1)), -4));
          //chmod($path2, 0755);
          self::copyPhpContents($path1, $path2);
        } elseif (is_file($path1)) {
          if (!copy($path1, $path2)) {
            throw new NgnException('Could not copy file, '.$path1.', to '.$path2);
          }
        }
        $i++;
      }
      closedir($dh);
      return true;
    } else {
      throw new NgnException('Could not open the directory "'.$dir1.'"');
    }
  }

  static public function getSize($path) {
    $size = 0;
    if (!$d = dir($path))
      throw new NgnException('Could not open the directory "'.$path.'"');
    while (false !== $entry = $d->read()) {
      if ($entry == '.' or $entry == '..') {
        continue;
      }
      if (is_dir($path . '/' . $entry))
        $size += self::getSize($path . '/' . $entry);
      else
        $size += filesize($path . '/' . $entry);
    }
    return $size;
  }

  ////////////////////////////////////////////////////
  

  private static $modifTime = 0;

  static public $lastModifExcept = array();

  static private function setLastModifTimeR($dirPath) {
    $d = dir($dirPath);
    while (($entry = $d->read()) !== false) {
      if ($entry == '.' or $entry == '..') {
        continue;
      }
      if (in_array($entry, self::$lastModifExcept))
        continue;
      if (!$mtime = filemtime($dirPath.'/'.$entry))
        return false;
      if ($mtime > self::$modifTime) {
        self::$modifTime = $mtime;
      }
      if (is_dir($dirPath.'/'.$entry)) {
        self::setLastModifTimeR($dirPath.'/'.$entry);
      }
    }
    $d->close();
    self::$modifTime;
  }

  static public function getLastModifTime($dirPath) {
    self::setLastModifTimeR($dirPath);
    return self::$modifTime;
  }

  static public function moveContents($from, $to, $replace = true) {
    if (! is_dir($from))
      throw new NgnException("'$from' is not dir");
    $from = Misc::clearLastSlash($from);
    $to = Misc::clearLastSlash($to);
    $d = dir($from);
    while (($entry = $d->read()) !== false) {
      if ($entry == '.' or $entry == '..') {
        continue;
      }
      $pathTo = ($to ? $to . '/' : '') . $entry;
      self::remove($pathTo);
      rename(($from ? $from . '/' : '') . $entry, $pathTo);
    }
  }

  static public function move($from, $to, $replace = true) {
    if ($replace) {
      output("Replace folder '$to' by '$from'");
    } else {
      output("Move folder '$from' by '$to'");
    }
    if ($replace and file_exists($to)) {
      if ($to == '.' or $to == '')
        throw new NgnException("Try to remove current dir '$to'");
      self::remove($to);
    }
    self::make($to);
    self::moveContents($from, $to);
    self::remove($from);
  }

  static public function chmod($dir, $prem) {
    chmod($dir, $prem);
    foreach (self::get($dir) as $name) {
      $path = $dir.'/'.$name;
      if (is_dir($path)) {
        chmod($path, $prem);
        self::chmod($path, $prem);
      } else
        chmod($path, $prem);
    }
  }
  
  static public function renameFileFolderContents($folder, $find, $replace) {
    output("Rename files, folders and their contents in '$folder' folder from '$find' to '$replace'");
    self::$includingDirs = true;
    foreach (self::getFilesR($folder) as $v) {
      $file = basename($v);
      if (is_file($v) and File::findContents($v, $find)) {
        File::replace($v, $find, $replace);
        output("Replace contents in '$v'");
      }
      if (strstr($file, $find)) {
        rename($v, dirname($v).'/'.str_replace($find, $replace, $file));
        output("Rename filename '$v'");
      }
    }
  }
  
  static function getOrderedFiles($folder, $globPattern = '*') {
    $files = glob("$folder/$globPattern");
    if (file_exists("$folder/order")) {
      foreach ($files as $v) $files2[basename($v)] = $v;
      return array_values(Arr::sortByArray($files2, File::strings("$folder/order")));
    }
    return $files;
  }

}