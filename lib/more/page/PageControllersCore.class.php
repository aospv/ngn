<?php

class PageControllersCore {
  
  static $propObjs;
  
  static function exists($name) {
    return DbModelCore::get('pages', $name, 'controller') !== false;
  }

  /**
   * Возвращает массив названий PageController'ов
   *
   * @return array  array('moduleControllerName' => 'moduleControllerTitle')
   */
  static function getTitles($onlyVisible = true) {
    $titles = array();
    foreach (ClassCore::getNames('Pcp') as $name) {
      $o = self::getPropObj($name, true);
      if ($onlyVisible and !$o->visible) continue;
      $titles[$name] = $o->title;
    }
    return $titles;
  }
  
  static function getProperties($name) {
    self::getPropObj($name, true)->getProperties();
  }
  
  static function getTitle($name) {
    return self::getPropObj($name)->getTitle();
  }
  
  static function isEditebleContent($name) {
    if (($o = self::getPropObj($name)) !== false)
      return $o->editebleContent;
    return false;
  }
  
  /**
   * @param  string  Имя контроллер
   * @return Pcp
   */
  static function getPropObj($name = '', $strict = false) {
    $class = ClassCore::nameToClass('Pcp', $name);
    return $strict ?
      O::get($class) :
      (O::exists($class) ? O::get($class) : false);
  }
  
  /**
   * Возвращает контроллер Раздела
   *
   * @param   array   Массив с данными Раздела
   */
  
  /**
   * Возвращает контроллер Раздела
   *
   * @param   array     Массив с данными Раздела
   * @return  CtrlPage  Возвращает объект контрллера или FALSE в случае его отсутсвия
   */
  static function getController(Dispatcher $oD, DbModelPages $page, array $options = array()) {
    $class = ClassCore::nameToClass('CtrlPage', $page['controller']);
    ClassCore::checkExistance($class);
    $ctrl = new $class($oD, $options); // Необходимо получать объект напрямую без кэширования
    $ctrl->setPage($page);
    return $ctrl;
  }
  
  /**
   * Произоводит преобразование массива настроек в зависимости от действий 
   * прописаных в классе Pcsa* соответствующего плагина
   *
   * @param   string  Имя плагина
   * @param   array   Массив исходных настроек
   * @return  array   Массив конечных настроек
   */
  static function settingsAction(DbModelPages $page, $initSettings) {
    if (empty($page['controller'])) return $initSettings;
    $class = 'Pcsa'.ucfirst($page['controller']);
    if (!O::exists($class)) return $initSettings;
    return O::get($class, $page)->action($initSettings);
  }
  
  static public function hasAncestor($controller, $ancestorController) {
    return ClassCore::hasAncestor(
      'CtrlPage'.ucfirst($controller), 'CtrlPage'.ucfirst($ancestorController));
  }
  
  static public function isMaster($controller) {
    return self::hasAncestor($controller, 'ddItemsMaster');
  }
  
  static public function getDefaultSettings($controller) {
    $class = ClassCore::nameToClass('Pcp', $controller);
    if (!O::exists($class)) return array();
    foreach(ClassCore::getAncestorNames($class, 'Pcp') as $name) {
      if (($v = Config::getVar('pcs.'.$name, true)) !== false)
        return $v;
    }
    return array();
  }
  
  static public function getVirtualCtrl($controller, Dispatcher $oDispatcher) {
    return O::get(ClassCore::nameToClass('CtrlPageV', $controller), $oDispatcher)->
      setPage(self::getVirtualCtrlPageModel($controller));
  }
  
  static public function virtualCtrlExists($controller) {
    return O::exists(ClassCore::nameToClass('CtrlPageV', $controller));
  }
  
  static public function getVirtualCtrlClass($controller) {
    return ClassCore::nameToClass('CtrlPageV', $controller);
  }
  
  static public function getVirtualCtrlPageModel($controller) {
    $class = self::getVirtualCtrlClass($controller);
    if (!O::exists($class)) return false;
    $virtualPageModel = new DbModelVirtual($class::getVirtualPage());
    $virtualPageModel->r['path'] = $controller; // path = controller (само-сабой)
    $virtualPageModel->r['module'] = $controller;
    $virtualPageModel->r['active'] = true;
    $virtualPageModel->r['id'] = 9999999999;
    return $virtualPageModel;
  }
  
  static protected $paths = array();
  
  /**
   * Возвращает путь до первой найденной страницы с указанным модулем.
   * Если у вас существует 2 страницы регастрации, то Tt::getControllerPath('userReg')
   * вернёт путь до страницы с меньшим ID.
   *
   * @param   string  Имя модуля
   * @return  string  Путь доя страницы
   */
  static public function getControllerPath($controller, $quietly = false) {
    if (($page = PageControllersCore::getVirtualCtrlPageModel($controller)) !== false)
      return $page->r['path'];
    if (isset(self::$paths[$controller])) return self::$paths[$controller];
    if (($page = DbModelCore::get('pages', $controller, 'controller')) !== false)
      return self::$paths[$controller] = $page->r['path'];
    if (!$quietly)
      throw new NgnException("Page with controller '$controller' not found");
    return '';
  }
  
}