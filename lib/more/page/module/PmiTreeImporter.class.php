<?php

class PmiTreeImporter {
  
  public $defaultParentId = 0;
  
  public $defaultOnMenu = false;
  
  public $createHomepageIfNotExists = true;
  
  public $createAtTheBottom = true;
  
  public $ids;
  
  private $n = 0;
  
  private $modules;

  public function __construct() {
    $this->modules = O::get('PageModules')->getItems();
    if (($page = DbModelPages::getHomepage()) !== false)
      $this->defaultParentId = $page['id'];
  }
  
  protected function createHomepage() {
    if ($this->createHomepageIfNotExists and empty($this->defaultParentId)) {
      $this->defaultParentId = DbModelCore::create('pages', array(
        'title' => 'Главная',
        'name' => 'main',
        'active' => 1,
        'folder' => 1,
        'onMenu' => 1,
        'onMap' => 1,
        'home' => 1
      ));
    }
  }

  /**
   * @param string
   * - Новости [news, ]
   * 
   * @param bool
   */
  public function import($text) {
    $oT2T = new Text2Tree();
    $oT2T->setText($text);
    if (!($nodes = $oT2T->getNodes())) return;
    $this->createHomepage();
    $this->ids = array();
    foreach ($nodes as $node) {
      $this->processNode($node);
    }
  }
  
  public function importNodes(array $nodes) {
    $this->ids = array();
    $this->createHomepage();
    foreach ($nodes as $node) {
      $this->processNode2($node);
    }
  }
  
  /**
   * Обрабатывает данные одного узла и создает раздел со всеми 
   * необходимыми настройками, структурами и подразделами
   *
   * @param array
   * 
   * Пример минимум:
   * array(
   *   'title' => 'Название раздела',
   *   'module' => 'pageModuleName'
   * )
   * 
   */
  public function processNode($v) {
    if (!isset($v['n'])) $v['n'] = 10;
    Arr::checkEmpty($v, array('title', 'module'));
    if (isset($v['parent']) and isset($this->ids[$v['parent']]))
      $v['parentId'] = $this->ids[$v['parent']];
    elseif ($this->defaultParentId)
      $v['parentId'] = $this->defaultParentId;
    elseif (empty($v['parentId']))
      $v['parentId'] = 0;
    if (!isset($v['oid'])) $v['oid'] = $this->n;
    if ($this->createAtTheBottom) {
      $v['oid'] = (int)db()->selectCell(
      'SELECT oid FROM pages WHERE parentId=?d ORDER BY oid DESC', $v['parentId']) + 10;
    }
    $oPmi = Pmi::get($v['module']);
    if ($v['module'] == 'link') Arr::checkEmpty($v, 'link');
    if (empty($v['name'])) $v['name'] = Misc::translate($v['title'], true);
    $v['onMenu'] = (int)$oPmi->onMenu;
    $pageId = $oPmi->install($v);
    $this->n += 10;
    $this->ids[$v['n']] = $pageId;
    return $pageId;
  }
  
  public function processNode2($v) {
    Arr::checkIsset($v, array('title', 'parent'));
    if (empty($v['module'])) $v['module'] = 'empty';
    if (isset($this->ids[$v['parent']])) $v['parentId'] = $this->ids[$v['parent']];
    else $v['parentId'] = $this->defaultParentId;
    $pageId = Pmi::get($v['module'])->install($v);
    $this->ids[$v['n']] = $pageId;
    return $pageId;
  }
  
  private function getModuleController($name) {
    if (!isset($this->modules[$name]))
      throw new NgnException("Module Package Module '$name' does not exists");
    return $this->modules[$name]['controller'];
  }
  
  public function deleteAllPages() {
    foreach (db()->selectCol('SELECT id FROM pages WHERE parentId=0') as $id) {
      DbModelCore::delete('pages', $id);
    }
  }
  
}

