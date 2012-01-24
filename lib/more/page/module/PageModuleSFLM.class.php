<?php

// работает только при наличии Pmi-класса к модулю

class PageModuleSFLM {

  protected $name, $module, $customWpath = array();
  
  /**
   * Массив точно ссылающийся на файлы $modulePath/$name.$type, в случае их наличия
   * @var array
   */
  protected $static;

  /**
   * Массив, ссылающийся на файлы, полученные из массива в файле $modulePath/$name.php
   * @var array
   * array(
   *   'depends' => 'moduleName',
   *   'css' => array(),
   *   'js' => array()
   * )
   */
  protected $dynamic;
  
  public $wpaths = array(
    'css' => array(),
    'js' => array()
  );
  
  /**
   * @var PageModuleInfo
   */
  protected $info;
  
  public function __construct($name, $module) {
    $this->name = $name;
    $this->module = $module;
    $this->info = O::get('PageModuleInfo', $module);
    if (file_exists("{$this->info->folderPath}/{$this->name}.php")) {
      $this->dynamic = include "{$this->info->folderPath}/{$this->name}.php";
      $this->initDynamic('css');
      $this->initDynamic('js');
    }
    if (isset($this->dynamic['depends'])) {
      $this->wpaths = O::get('PageModuleSFLM', $name, $this->dynamic['depends'])->wpaths;
    }
    $this->addStaticPath('css');
    $this->addStaticPath('js');
    $this->addDynamicPaths('css');
    $this->addDynamicPaths('js');
  }
  
  protected function initDynamic($type) {
    if (!isset($this->dynamic[$type])) return;
    foreach ($this->dynamic[$type] as &$v) {
      // Если в пути нет слэша, значит этот файл находится в папке текущего модуля
      if (!strstr($v, '/')) $v = $this->info->folderWpath.'/'.$v;
    }
  }
  
  protected function addStaticPath($type) {
    if (($paths = $this->info->getFilePaths("{$this->name}.$type")) === false) return;
    $this->wpaths[$type][] = $paths[1];
  }
  
  protected function addDynamicPaths($type) {
    if (isset($this->dynamic[$type]))
      $this->wpaths[$type] = Arr::append($this->wpaths[$type], $this->dynamic[$type]);
  }
  
  protected function addBlocksPaths($type) {
    // ---
  }
  
  public function html() {
    $html = '';
    foreach ($this->wpaths as $type => $wpaths)
      foreach ($wpaths as $wpath)
        $html .= $this->_html($wpath, $type);
    return $html;
  }

  protected function _html($wpath, $type) {
    return $type == 'css' ?
      '<link rel="stylesheet" type="text/css" href="/'.
        $wpath.'?'.BUILD.'" media="screen, projection" />'."\n" :
      '<script src="/'.$wpath.'?'.BUILD.'" type="text/javascript"></script>'."\n";
    ;
  }

}