<?php

class Config {

  static $tab = "  ";

  static function regexp($func, $k) {
    return str_replace('{f}', $func,
      str_replace('{k}', $k,
        '/{f}\s*\(\s*[\'"]{k}["\'],\s*(.*)\s*\)\s*;/i'
    ));
  }
  
  static function clearQuotes($v) {
    return preg_replace('/[\'"]?(.*)[\'"]?/U', '$1', $v);
  }
  
  static public function __getConstant($c, $k) {
    if (preg_match(self::regexp('define', $k), $c, $m)) {
      return self::clearQuotes($m[1]);
    } elseif (preg_match(self::regexp('setConstant', $k), $c, $m)) {
      return self::clearQuotes($m[1]);
    }
    return self::noConst;
  }
  
  static public function _updateConstant($c, $k, $v, $formatValue = true) {
    foreach (self::$funcs as $func) {
      $c = preg_replace_callback(
        self::regexp($func, $k),
        function($m) use ($func, $k, $v, $formatValue) {
          return $func."('$k', ".($formatValue ? Arr::formatValue($v) : $v).");";
        },
        $c
      );
    }
    return $c;
  }
  
  static $constantsRegexp = 'define\([\'"](.*)["\'],\s*(.*)\)\s*;';
  
  static $vars;

  static $funcs = array('define', 'setConstant');
  
  static public function updateConstant($file, $k, $v, $formatValue = true) {
    $c = self::_updateConstant(file_get_contents($file), $k, $v, $formatValue);
    file_put_contents($file, $c);
  }

  static public function updateConstants($file, $constants, $formatValue = true) {
    $c = file_get_contents($file);
    foreach ($constants as $k => $v) {
      if (! isset($v))
        throw new NgnException("[$k] not defined");
      $c = self::_updateConstant($c, $k, $v, $formatValue);
    }
    file_put_contents($file, $c);
  }

  static public function addConstant($file, $k, $v) {
    file_put_contents($file,
      self::_addConstant(file_get_contents($file), $k, $v));
  }
  
  static public function _addConstant($c, $k, $v) {
    $c = trim($c);
    if (substr($c, strlen($c) - 2, 2) == '?>') {
      // убираем закрывающий PHP-тэг в конце
      $c = substr($c, 0, strlen($c) - 2);
      $c = trim($c);
    }
    return $c."\n\n"."if (!defined('$k')) define('$k', ".Arr::formatValue($v).");";
  }
  
  static public function replaceConstant($file, $k, $v) {
    self::deleteConstant($file, $k);
    self::addConstant($file, $k, $v);
  }
  
  static public function _replaceConstant($c, $k, $v) {
    $c = self::_deleteConstant($c, $k);
    $c = self::_addConstant($c, $k, $v);
    return $c;
  }
  
  static public function replaceConstants($file, $constants) {
    $c = file_exists($file) ? file_get_contents($file) : "<?php\n";
    foreach ($constants as $k => $v) {
      $c = self::_deleteConstant($c, $k);
      $c .= "\n\n" . "if (!defined('$k')) define('$k', ".Arr::formatValue($v).");";
    }
    file_put_contents($file, $c);
  }
  
  static public function cleanupConstants($file) {
    $constants = self::getConstants($file);
    ksort($constants);
    foreach ($constants as $k => $v)
      $c .= "\n\n"."if (!defined('$k')) define('$k', " . Arr::formatValue($v) . ");";
    file_put_contents($file, "<?php$c");
  }

  static public function deleteConstant($file, $k) {
    file_put_contents($file, self::_deleteConstant(file_get_contents($file), $k));
  }
  
  static public function _deleteConstant($c, $k) {
    $r = preg_replace(
      self::regexp('\s*if\s*\(\s*!defined\([\'"]'.$k.'[\'"]\s*\)\s*\)\s* define', $k), '', $c);
    return preg_replace(self::regexp('setConstant', $k), '', $r);
  }

  /**
   * Получает список всех констант, используемых в файле
   *
   * @param   string  Путь к файлу
   * @return  array
   */
  static public function getConstants($file, $quietly = false) {
    if (!file_exists($file)) {
      if (!$quietly) throw new NgnException("File '$file' does not exists");
      else return false;
    }
    return self::parseConstants(file_get_contents($file));
  }
  
  static public function parseConstants($s) {
    preg_match_all('/'.self::$constantsRegexp.'/i', $s, $m);
    if (!$m[1]) return false;
    $constants = array();
    for ($i = 0; $i < count($m[1]); $i++) {
      $constants[$m[1][$i]] = Arr::deformatValue($m[2][$i]);
    }
    return $constants;
  }

  static public function getAllConstants($folder) {
    if ($folder[strlen($folder) - 1] == '/')
      $folder = substr($folder, 0, strlen($folder) - 1);
    $items = array();
    foreach (Dir::files($folder) as $entry) {
      $key = str_replace('.php', '', $entry);
      if (! $constants = self::getConstants($folder . '/' . $entry))
        continue;
      $items[$key] = $constants;
    }
    return $items;
  }
  
  static public function getAllConstantsFlat($folder) {
    $r = array();
    foreach (self::getAllConstants($folder) as $constants) {
      foreach ($constants as $name => $value) {
        $r[$name] = $value;
      }
    }
    return $r;
  }

  static public function loadConstants($name) {
    if (($filePath = self::getFilePath($name, 'constants')) !== false) {
      include_once $filePath;
    } else {
      throw new NgnException('"config/constants/' . $name . '.php" not found');
    }
  }

  static public function getVars($folder) {
    return self::_getVars($folder, true);
  }

  static public function getVarConfigs($folder) {
    return self::_getVars($folder, false);
  }

  static public function _getVars($folder, $vars = true) {
    if (!is_dir($folder)) return false;
    foreach (Dir::files($folder) as $file) {
      $key = str_replace('.php', '', $file);
      if ($vars)
        $items[$key] = self::getVar($key, true);
      else
        $items[] = $key;
    }
    return $items;
  }
  
  const noConst = 311111;

  static public function getConstant($file, $k, $quitely = false) {
    if (!file_exists($file)) {
      if ($quitely) return false;
      else throw new NoFileException($file);
    }
    if (($r = self::__getConstant(file_get_contents($file), $k)) != self::noConst)
      return $r;
    if ($quitely) return false;
    else throw new NgnException("There is no constant '$k' in file '$file'");
  }
  
  static public function _getConstant($c, $k, $quitely = false) {
    if (($r = self::__getConstant($c, $k)) != self::noConst)
      return $r;
    if ($quitely) return false;
    else throw new NgnException("There is no constant '$k'");
  }

  static public function updateVar($file, $v, $createDirs = false) {
    require_once 'Zend/Config.php';
    require_once 'Zend/Config/Writer/Array.php';
    $config = new Zend_Config($v, true);
    $writer = new Zend_Config_Writer_Array();
    if ($createDirs) Dir::make(dirname($file));
    $writer->setConfig($config)->setFilename($file)->write();
  }
  
  static public function updateSubVar($file, $k, $v) {
    $r = file_exists($file) ? include $file : array();
    $r[$k] = $v;
    self::updateVar($file, $r);
  }
  
  /**
   * Возвращает массив с данными конфигурации
   *
   * @param   string  Имя файла с массивом $_CONFIG и одновременно ключа в массиве $_CONFIG
   * @return  array
   */
  static public function getVar($key, $quietly = false) {
    if (isset(self::$vars[$key]))
      return self::$vars[$key];
    if (($filePath = self::getFilePath($key, 'vars')) !== false) {
      $r = include $filePath;
    } else {
      if (!$quietly)
        throw new NgnException("Var '$key' not found");
      return false;
    }
    self::$vars[$key] = $r;
    return $r;
  }
  
  static public function getVarVar($k1, $k2, $quietly = false) {
    if (!($v = self::getVar($k1, $quietly))) return false;
    if (!isset($v[$k2])) {
      if (!$quietly) throw new NgnException("\$v[$k2] not defined");
      else return false;
    }
    return $v[$k2];
  }
  
  static public function getSubVar($key, $subKey) {
    $v = self::getVar($key);
    return isset($v[$subKey]) ? $v[$subKey] : null;
  }

  static public function getConstantsFilePath($path) {
    return self::getFilePath($path, 'constants');
  }

  static public function getFilePath($path, $folder) {
    $path = $path.'.php';
    if (file_exists(SITE_PATH.'/config/'.$folder.'/'.$path))
      return SITE_PATH.'/config/'.$folder.'/'.$path;
    if (defined('SITE_SET') and file_exists(NGN_PATH.'/config/siteSet/'.SITE_SET.'/'.$folder.'/'.$path))
      return NGN_PATH.'/config/siteSet/'.SITE_SET.'/'.$folder.'/'.$path;
    elseif (file_exists(NGN_PATH.'/config/base/'.$folder.'/'.$path))
      return NGN_PATH.'/config/base/'.$folder.'/'.$path;
    else
      return false;
  }

  static public function getFileVar($file, $quietly = true) {
    if (file_exists($file))
      return include ($file);
    else {
      if (!$quietly)
        throw new NgnException("Path '$file' not found");
      return false;
    }
  }

  static public function var2declaration($var, $depth = 1) {
    if (is_array($var)) {
      foreach ($var as $k => $v) {
        $_[] = str_repeat(
          self::$tab, $depth).(is_numeric($k) ? $k : "'$k'").
           " => ".self::var2declaration($v, $depth + 1);
      }
      return "array(\n".implode(",\n", $_)."\n".
        str_repeat(self::$tab, $depth - 1).");";
    } else {
      return Arr::formatValue($var);
    }
  }
  
  //-------------------------------------------------------------------------------
  
  /**
   * Возвращает массив с существующими структурами конфигурационных констант или переменных
   *
   * @param   string  Путь до каталога "ngn" или "site"
   * @param   string  "constants" / "vars"
   * @return  array
   */
  static public function getStruct($folder, $type) {
    if (!file_exists($folder.'/config/struct/'.$type.'.php'))
      return array();
    return include $folder.'/config/struct/'.$type.'.php';
  }
  
  // --------------------------------------------------------------------------------
  
  static public function createConstants($file, $constants) {
    file_put_contents($file, self::createConstantsStr($constants));
  }
  
  static public function createConstantsStr($constants) {
    $c = "<?php\n";
    foreach ($constants as $k => $v) {
      $c .= "\n\n" . "if (!defined('$k')) define('$k', " . Arr::formatValue($v) . ");";
    }
    return $c;
  }
  
}