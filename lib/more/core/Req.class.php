<?php

define('PAGE_PARAM_TYPE_ID', 1);
define('PAGE_PARAM_TYPE_NAME', 2);
define('PAGE_PARAM_TYPE_DATE', 3);

class Req extends Options {

  /**
   * Пуьт к файлу, очищенный от всякого мусора
   *
   * @var string
   */
  public $initPath;
  
  public $path;

  /**
   * Параметры, начиная с первого нужного
   *
   * @var array
   */
  public $params = array();

  /**
   * Исходные параметра
   *
   * @var array
   */
  public $initParams;

  /**
   * Есть ли слэш на конце URL'а
   *
   * @var bool
   */
  public $lastSlash = false;

  public $page;

  /**
   * @var $_REQUEST
   */
  public $r;

  /**
   * @var $_POST
   */
  public $p;
  
  /**
   * @var $_FILES
   */
  public $files;
  
  public function __construct(array $options = array()) {
    $this->setOptions($options);
    $requestUri = isset($this->options['requestUri']) ?
      $this->options['requestUri'] : $_SERVER['REQUEST_URI'];
    // Берём путь из REQUEST_URI
    list($path) = explode('?', $requestUri);
    if ($path[0] == '/')
      $path = substr($path, 1, strlen($path)); // Убираем первый слэш
    if ($path[strlen($path) - 1] == '/') {
      $path = substr($path, 0, strlen($path) - 1); // Убираем первый слэш
      $this->lastSlash = true;
    }
    $this->initPath = $path;
    $this->setPathParams();
    $this->path = implode('/', $this->params);
    if ($this->params) {
      foreach ($this->params as $p) {
        if (preg_match('/pg([a-z]*)(\d+)/', $p, $m)) {
          $this->page[$m[1]] = $m[2];
        }
      }
    }
    $new = array();
    foreach ($_REQUEST as $k => $v) $new[str_replace('amp;', '', $k)] = $v;
    $this->r = $new;
    if (!empty($this->r['a'])) $this->r['action'] = $this->r['a'];
    $this->p = $_POST;
    $this->files = self::convertFiles($_FILES);
  }
  
  static public function convertFiles(array $FILES) {
    $files = array();
    foreach ($FILES as $key => $data)
      $files[$key] = self::fixFilesArray($data);
    return $files;
  }

  static protected function fixFilesArray($data) {
    if (!isset($data['tmp_name']) or !is_array($data['tmp_name']))
      return $data;
    $fileKeys = array('error', 'name', 'size', 'tmp_name', 'type');
    $files = $data;
    foreach ($fileKeys as $k) unset($files[$k]);
    foreach (array_keys($data['tmp_name']) as $key) {
      $files[$key] = self::fixFilesArray(array(
        'error' => isset($data['error'][$key]) ? $data['error'][$key] : null,
        'name' => isset($data['name'][$key]) ? $data['name'][$key] : null,
        'type' => isset($data['type'][$key]) ? $data['type'][$key] : null,
        'tmp_name' => $data['tmp_name'][$key],
        'size' => isset($data['size'][$key]) ? $data['size'][$key] : null
      ));
    }
    return $files;
  }
  
  /**
   * Разбирает строку пути к странице со слэшами на параметры
   *
   * @param   string  Строка в формате "12/74324/56432"
   * @return  array   Разобранные из строки параметры
   */
  private function setPathParams() {
    if ($this->params) return $this->params;
    $this->params = array();
    if (! $this->initPath)
      return false;
    $s = $this->initPath;
    $params = explode('/', $s);
    $this->initParams = $params;
    $newParams = array();
    $n = 0;
    if (defined('FIRST_URL_PARAM_N')) {
      for ($i = FIRST_URL_PARAM_N; $i < count($params); $i++) {
        $newParams[$n] = $params[$i];
        $n++;
      }
      $this->params = $newParams;
    } else {
      $this->params = $this->initParams;
    }
    return $this->params;
  }

  /**
   * Получает тип параметра
   *
   * @param   mixed   Параметр
   * @return  integer Тип параметра
   */
  private function getParamType($param) {
    if ((int)substr($param, 0, 1) and strstr($param, '-') and (int)$param)
      return PAGE_PARAM_TYPE_DATE;
    elseif ((int) substr($param, 0, 1))
      return PAGE_PARAM_TYPE_ID;
    else
      return PAGE_PARAM_TYPE_NAME;
  }
  
  protected $base;
  
  public function getBase() {
    if (isset($this->base)) return $this->base;
    $firstParamN = defined('FIRST_URL_PARAM_N') ? FIRST_URL_PARAM_N : 0;
    $p = array();
    for ($i = 0; $i < $firstParamN; $i++) {
      $p[] = $this->initParams[$i];
    }
    return $this->base = implode('/', $p);
  }
  
  public function getAbsBase() {
    return 'http://'.SITE_DOMAIN;
  }
  
  public function getUrlDeletedParams($url, $params) {
    return Tt::getUrlDeletedParams($url, $params);
  }

  public function rq($name) {
    if (!isset($this->r[$name])) throw new NgnException("\$_REQUEST[$name] not defined");
    return $this->r[$name]; 
  }
  
  public function reqNotEmpty($name) {
    if (empty($this->r[$name])) throw new NgnException("\$_REQUEST[$name] can not be empty. URI: ".$_SERVER['REQUEST_URI'].'. r: '.getPrr($this->r));
    return $this->r[$name]; 
  }

  public function reqAnyway($name) {
    if (empty($this->r[$name])) return '';
    return $this->r[$name];
  }

}
