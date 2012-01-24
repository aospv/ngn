<?php

abstract class Pmi extends Options {
  
  protected $module;
  public $title;
  public $description;
  public $controller = '';
  public $onMenu = true;
  public $updateControllerAfterNodeCreate = false;
  public $oid = 0;
  protected $requiredProperties = array();
  protected $behaviorNames = array();
  protected $pageBlocks = array();
  
  public function __construct() {
    $this->module = ClassCore::classToName('Pmi', $this);
    if ($this->controller == 'module') $this->controller = $this->module;
    Misc::checkEmpty($this->title);
    foreach ($this->requiredProperties as $name)
      if (!isset($this->$name))
        throw new NgnException("\$this->$name not defined. Class: ".get_class($this));
  }
  
  protected $pageId;
  
  /**
   * Создает раздел
   *
   * @param   integer   Пример:
   *                    array(
   *                      'title' => 'Бла бла бла',
   *                      'name' => 'bla-bla',
   *                      'children' => array(
   *                        ...
   *                      ),
   *                      'active' => 1,
   *                      'onMenu' => 1,
   *                      'parentId' => 1,
   *                      'oid' => 10
   *                    )
   * 
   */
  protected function createNode($v) {
    $v['module'] = $this->module;
    if (!$this->updateControllerAfterNodeCreate) $v['controller'] = $this->controller;
    $v['settings'] = $this->getSettings();
    $v['onMap'] = 1;
    $v['onMenu'] = !empty($v['onMenu']) ? $v['onMenu'] : $this->onMenu;
    $v['active'] = 1;
    $v['folder'] = !empty($v['folder']) ? 1 : (empty($v['children']) ? 0 : 1);
    $v['name'] = !empty($v['name']) ? $v['name'] : Misc::translate($v['title'], true);
    unset($v['n']);
    $pageId = DbModelCore::create('pages', $v, true);
    if ($this->updateControllerAfterNodeCreate)
      db()->query('UPDATE pages SET controller=? WHERE id=?d', $this->controller, $pageId);
    return $this->pageId = $pageId;
  }
    
  protected function getSettings() {
    return array();
  }
  
  protected function afterCreate($node) {
    foreach (ClassCore::getObjectsByNames('PmiBehavior', $this->behaviorNames) as $o) {
      $o->action($this->pageId, $node);
    }
  }
  
  /**
   * Инсталлирует раздел
   * Пример массива $node:
   * Array (
   *   [oid] => 11
   *   [title] => Оригинальные картриджи
   *   [parentId] => 10
   *   [onMenu] => true
   * )
   *
   * @param   array
   * @return  integer   ID раздела
   */
  public function install($node) {
    $this->createNode($node);
    $this->afterCreate($node);
    $this->createPageBlocks();
    return $this->pageId;
  }
  
  protected function createPageBlocks() {
    foreach ($this->pageBlocks as $v) {
      $params = array(
        'type' => $v['type'],
        'ownPageId' => $this->pageId,
        'colN' => 1,
        'global' => false
      );
      if (!empty($v['params'])) $params = array_merge($params, $v['params']);
      $oMM = new PageBlockModelManager(
        PageBlockCore::getStructure($v['type'])->setPreParams(array(
          'pageId' => $this->pageId
        )),
        $params
      );
      $oMM->create($v['settings']);
    }
  }
  
  /**
   * @param  string $module
   * @return Pmi
   */
  static public function get($module) {
    return O::get('Pmi'.ucfirst($module));
  }
    
  /**
   * @param  string $module
   * @return Pmi
   */
  static public function take($module) {
    return O::take('Pmi'.ucfirst($module));
  }
  
}
