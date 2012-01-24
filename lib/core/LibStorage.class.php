<?php

class LibStorage {
  
  /**
   * Хранилище кода билиотек
   *
   * @var string
   */
  private $code = "<?php\n\n";
  
  /**
   * Массив с сохраненными в хранилище файлами
   *
   * @var array
   */
  private $storedFiles = array();
  
  private $storedClasses = array();
  
  private $zendFramworkPathtypeLibs = array('Zend', 'Dklab', 'DbSimple');
  
  private $nonStrictIncludeModePathtypeLibs = array('Cache');
  
  /**
   * Массив с файлами, 
   *
   * @var array
   */
  private $storedFilesPrepare = array();
  
  private $includePaths;
  
  private $systemClasses = array(
    'stdClass',
    'Exception',
    'ErrorException',
    'COMPersistHelper',
    'com_exception',
    'com_safearray_proxy',
    'variant',
    'com',
    'dotnet',
    'DateTime',
    'DateTimeZone',
    'ReflectionException',
    'Reflection',
    'ReflectionFunctionAbstrac',
    'ReflectionFunction',
    'ReflectionParameter',
    'ReflectionMethod',
    'ReflectionClass',
    'ReflectionObject',
    'ReflectionProperty',
    'ReflectionExtension',
    'LibXMLError',
  	'Memcache',
    '__PHP_Incomplete_Class',
    'php_user_filter',
    'Directory',
    'SimpleXMLElement',
    'DOMException',
    'DOMStringList',
    'DOMNameList',
    'DOMImplementationList',
    'DOMImplementationSource',
    'DOMImplementation',
    'DOMNode',
    'DOMNameSpaceNode',
    'DOMDocumentFragment',
    'DOMDocument',
    'DOMNodeList',
    'DOMNamedNodeMap',
    'DOMCharacterData',
    'DOMAttr',
    'DOMElement',
    'DOMText',
    'DOMComment',
    'DOMTypeinfo',
    'DOMUserDataHandler',
    'DOMDomError',
    'DOMErrorHandler',
    'DOMLocator',
    'DOMConfiguration',
    'DOMCdataSection',
    'DOMDocumentType',
    'DOMNotation',
    'DOMEntity',
    'DOMEntityReference',
    'DOMProcessingInstruction',
    'DOMStringExtend',
    'DOMXPath',
    'RecursiveIteratorIterator',
    'IteratorIterator',
    'FilterIterator',
    'RecursiveFilterIterator',
    'ParentIterator',
    'LimitIterator',
    'CachingIterator',
    'RecursiveCachingIterator',
    'NoRewindIterator',
    'AppendIterator',
    'InfiniteIterator',
    'RegexIterator',
    'RecursiveRegexIterator',
    'EmptyIterator',
    'ArrayObject',
    'ArrayIterator',
    'RecursiveArrayIterator',
    'SplFileInfo',
    'DirectoryIterator',
    'RecursiveDirectoryIterato',
    'SplFileObject',
    'SplTempFileObject',
    'SimpleXMLIterator',
    'LogicException',
    'BadFunctionCallException',
    'BadMethodCallException',
    'DomainException',
    'InvalidArgumentException',
    'LengthException',
    'OutOfRangeException',
    'RuntimeException',
    'OutOfBoundsException',
    'OverflowException',
    'RangeException',
    'UnderflowException',
    'UnexpectedValueException',
    'SplObjectStorage',
    'XMLReader',
    'XMLWriter',
    'PDOException',
    'PDO',
    'PDOStatement',
    'PDORow',
    'SQLiteDatabase',
    'SQLiteResult',
    'SQLiteUnbuffered',
    'SQLiteException',
    'tidy',
    'tidyNode',
    'XSLTProcessor',
    'finfo'
  );
  
  public $ignoreClasses = array(
    'NgnCache'
  );
  
  private $depth = 0;

  public function __construct() {
    $this->systemClasses[] = 'ZipArchive';
    foreach ($this->systemClasses as &$c) $c = strtolower($c);
    $this->includePaths = explode(';', get_include_path());
    $this->includePaths = array_map(array('Misc', 'clearLastSlash'), $this->includePaths);
  }
  
  /**
   * Сохраняет код файла библиотеки в хранилище
   * Возвращает true в случае успешного сохранения файла
   * Возвращает false в случае не сохранения файла
   *  
   * @param   string  Относительный путь к библиотеке либо имя класса
   * @return  bool    
   */  
  public function addLib($path) {
    $this->addFile(Lib::getPath($path));
  }
  
  private function fileExists($filepath) {
    if (file_exists($filepath))
      return $filepath;
    else {
      foreach ($this->includePaths as $includePath) {
        if (file_exists($includePath . '/' . $filepath))
          return $includePath . '/' . $filepath;
      }
    }
    return false;
  }
  
  /**
   * Сохраняет код файла по абсолютному пути в хранилище
   * Возвращает true в случае успешного сохранения файла
   * Возвращает false в случае не сохранения файла
   *  
   * @param   string  Абсолютный путь
   * @return  bool    
   */  
  public function addFile($filepath) {
    if (!($_filepath = $this->fileExists($filepath)))
      throw new NgnException("File '$filepath' not exists");
    $filepath = $_filepath;
    if (strstr($filepath, '//')) die2($filepath.'***');
    if (in_array($filepath, $this->storedFiles)) // Если файл содержится в хранилище...
      return false;
    output(str_repeat('- ', $this->depth)."Add file '$filepath' code");
    $this->storedFilesPrepare[] = $filepath;
    $code = $this->getCode($filepath);
    $this->depth++; 
    $this->prepareCode($code, $filepath);
    $this->depth--;
    $this->storedFiles[] = $filepath;
    $this->_addCode($code);
    return true;
  }
  
  public function addCode($code) {
    $this->prepareCode($code);
    $this->_addCode($code);
  }
  
  protected function _addCode($code) {
    $this->code .= $code . "\n";
  }
  
  /**
   * Возвращает код из хранилища
   *
   * @return string
   */
  public function get() {
    return $this->code;
  }
  
  /**
   * Получает код из файла и подготавливает его для добавления в хранимлище
   *
   * @param   string  Путь к файлу
   */
  public function getCode($filepath) {
    return self::stripPhpTag(trim(file_get_contents($filepath)), $filepath);
  }
  
  static public function stripPhpTag($c, $filepath = false) {
    // Убираем PHP-тэг из начала файла
    $c = preg_replace('/^<\?php/m',
      ($filepath ? "\n// -- File: $filepath" : ''), $c);
    $c = preg_replace('/^<\?/m',
      ($filepath ? "\n// -- File: $filepath" : ''), $c);
    // Убираем PHP-тэг из конца файла
    return preg_replace('/\?>$/m', '', $c);
  }
  
  /**
   * Подготавливает код, очищая включенный в нём библиотеки при этом, 
   * обрабатывая их тоже
   *
   * @param   string  Код из файла
   * @param   string  Абсолютный путь к файлу, код от которого передан в ссылке &$code
   *                  Опциональный параметр. Используется только в сообщении об ошибке 
   */
  private function prepareCode(&$code, $file = null) {
    $code = preg_replace('/^.*\@LibStorageRemove.*$/m', '', $code);
    $code = TextParsing::stripComments($code);
    // Ещё на уровне подготовки кода. Если в нём присутствует объявление класса,
    // добавляем этот класс в $this->storedClasses
    $this->prepareStoredClasses($code);
    $this->prepareCodeIncludes($code, $file);
    $this->prepareCodeLibs($code, $file);
    $this->prepareCodeClasses($code, $file);
  }
  
  /**
   * Добавляем в массив сохраненных классов, объявленные в этом коде
   *
   * @param   string  Код
   */
  private function prepareStoredClasses(&$code) {
    //preg_match_all('/^\s*class\s*([a-zA-Z_]+).*$/', $code, $m);
    if (preg_match_all('/^\s*class\s*([a-zA-Z_]+).*$/m', $code, $m))
      foreach ($m[1] as $class) {
        // Добавляем напрямую, потому что, если класс только ещё объявляется
        // то его точно не должно быть в массиве сохраненных
        $this->storedClasses[] = $class;
      }
  }
  
  /**
   * @param   string  Ссылка на строку с кодом без тэгов <?php
   * @param   string  $file
   */
  private function prepareCodeIncludes(&$code, $file = null) {
    if (preg_match_all('/^\s*require_once'.
    '\s+(?=\(|)([a-zA-Z0-9\/. _\'"]*)(?=\)|)\s*;\s*$/m', $code, $m)) {
      foreach ($m[1] as $_filepath) { // $filepath в формате "LIB_PATH . 'core/Dir.class.php'"
        if (strstr($_filepath, "'Cache' . ")) continue; // Exclude for DbSimple "Generic.php" file
        $_filepath = eval('return '.$_filepath.';');
        $this->addPreparedCode($_filepath);
      }
    }
    // Убираем все инклюды
    $code = preg_replace(
      '/^\s*require_once\s+([()a-zA-Z0-9\/. _\'"]*)(?=\)|)\s*;\s*$/m',
                          // ^^ 
      '', $code);
  }
  
  private function prepareCodeLibs(&$code, $file = null, $prepareClasses = false) {
    if (preg_match('/Lib::required\("([^"]*)"\);/', $code))
      throw new NgnException('Lib::required() must contain only single quotes');
    preg_match_all('/Lib::required\(\'([^\']*)\'\);/', $code, $m);
    // $m[1] - массив со всеми файлами, подключаемыми в файле $path
    if (!$m[1]) return;
    foreach ($m[1] as $path) {
      $this->prepareCodeLib($path, $file);
      // Убираем подключение
      $path = str_replace('/', '\/', $path);
      $path = str_replace('.', '\\.', $path);
      $code2 = preg_replace(
        '/Lib::required\([\'"]' . $path . '[\'"]\);\s*\n/', 
        '', $code);
    }
  }
  /**
   * ----
   *
   * @param   string  Варианты:
   *                    - path/to/lib/Name.class.php
   *                    - Name  
   */
  private function prepareCodeLib($path, $file = null) {
    // Необходимо учесть вариант, когда несколько классов находятся в одном файле
    // Тогда присутствие файла в.............. 
    $filepath = Lib::getPath($path, false);
    if (!$filepath) {
      // Если путь является классом, разрешенным к неиспользованию, не обрабатываем его
      if ($this->nonStrictClassInclude($path)) return;
      // Иначе ошибка!
      else throw new NgnException("Path '$path' not found".($file ? " in file '$file'" : ''));
    }
    //"\nCode:\n##############################################################\n$code");
    $this->addPreparedCode($filepath);
  }
  
  private function addPreparedCode($filepath) {
    if (!$filepath) throw new NgnException("\$filepath is EMPTY!");
    if (!in_array($filepath, $this->storedFilesPrepare)) {
      $this->addFile($filepath);
    }
  }
  
  private function isSystemClass($class) {
    return in_array(strtolower($class), $this->systemClasses);
  }
  private function isIgnoreClass($class) {
    return in_array($class, $this->ignoreClasses);
  }
  private function isKeyword($class) {
    return in_array($class, array('self', 'parent', 'static'));
  }
  private function isStoredClass($class) {
    return in_array($class, $this->storedClasses);
  }
  
  private function prepareCodeClasses($code, $file = null) {
    preg_match_all('/new\s+([a-zA-Z][a-zA-Z0-9_]*)/m', $code, $m1);
    preg_match_all('/extends\s+([a-zA-Z][a-zA-Z0-9_]*)/m', $code, $m2);
    preg_match_all('/([a-zA-Z][a-zA-Z0-9_]*)::[a-zA-Z][a-zA-Z0-9_]*/m', $code, $m3);
    if (!$m1[1] and !$m2[1] and !$m3[1]) return;
    $classes = array();
    if (isset($m1[1])) $classes = Arr::append($classes, $m1[1]);
    if (isset($m2[1])) $classes = Arr::append($classes, $m2[1]);
    if (isset($m3[1])) $classes = Arr::append($classes, $m3[1]);
    foreach ($classes as $class) {
      if ($this->isStoredClass($class)) continue;
      if ($this->isKeyword($class)) continue;
      //if ($class == 'Zip') prr($class);
      if ($this->isSystemClass($class)) continue;
      if ($this->isIgnoreClass($class)) continue;
      $this->storedClasses[] = $class; // Класс в хранилище. Второй раз включён уже не будет
      //print "************* $class\n";
      $this->prepareCodeLib(
        $this->transformZendFramewrokClass($class),
        $file
      );
    }
  }
  
  /**
   * Определяет является ли подключение данного класса не обязательным.
   * Использует для проверки имя библиотеки из пути к классу в стиле Zend Framework
   *
   * @param   string  Имя класса
   * @return  bool
   */
  private function nonStrictClassInclude($class) {
    $libName = preg_replace('/([a-zA-Z][a-zA-Z0-9]*)_([a-zA-Z][a-zA-Z0-9_]*)/', '$1', $class);
    return in_array($libName, $this->nonStrictIncludeModePathtypeLibs);
  }
  
  private function transformZendFramewrokClass($class) {
    $libName = preg_replace('/([a-zA-Z][a-zA-Z0-9]*)_([a-zA-Z][a-zA-Z0-9_]*)/', '$1', $class);
    if (in_array($libName, $this->zendFramworkPathtypeLibs))
      $class = str_replace('_', '/', $class.'.php');
    return  $class;
  }
  
  /*
  private function prepareCodeStaticClasses(&$code2, $file) {
    if (!$m[1]) return;
    foreach ($m[1] as $class) {
      if ($this->isKeyword($class)) continue;
      if ($this->isSystemClass($class)) continue;
      if ($this->isIgnoreClass($class)) continue;
      $class = $this->transformZendFramewrokClass($class);
      $this->prepareCodeLib($code2, $class, $file);
    }
  }
  */
  
  /**
   * Добавляет класс по пути к файлу в хранилище, если это класс
   *
   * @param   string  Путь к файлу класса
   */
  private function addStoredClass($filepath) {
    //if (strstr($filepath))
  }
  
  // ==========================================================================
  
  static public function buildPackage($ngnPath, $name) {
    output("Build package '$name'");
    $c = "<?php\n\n";
    $r = array();
    foreach (glob($ngnPath.'/lib/more/'.$name.'/*.class.php') as $file) {
      $r[] = array(
        'parentsCnt' => count(ClassCore::getParents(
          str_replace('.class.php', '', basename($file)))),
        'file' => $file
      );
    }
    foreach (Arr::sort_by_order_key($r, 'parentCnt') as $v) {
      $c .= self::stripPhpTag(file_get_contents($v['file']));
    }
    file_put_contents(LIB_PATH.'/package/'.$name.'.php', $c);
  }
  
}