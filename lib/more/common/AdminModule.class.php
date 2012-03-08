<?php

class AdminModule {
  
  static public $forseListAllow = false;
  
  static function getModules() {
    $modules = array();
    $order = array();
    foreach (ClassCore::getClassesByPrefix('CtrlAdmin') as $k => $class) {
      if (($prop = $class::getProperties()) === false) continue;
      $prop['name'] = ClassCore::classToName('CtrlAdmin', $class);
      $order[$k] = isset($prop['order']) ? $prop['order'] : 100;
      $modules[$k] = $prop;
    }
    array_multisort($order, SORT_ASC, $modules);
    return $modules;
  }
  
  static function getListModules($onMenu = false) {
    return Arr::filter_func(self::getModules(), function($v) {
      if (empty($v['onMenu'])) return false;
      return AdminModule::isListAllowed($v['name']);
    });
  }
  
  static function isAllowed($module) {
    if (Misc::isGod()) return true;
    return self::_isAllowed($module);
  }
  
  static function isListAllowed($module) {
    if (O::get('Req')->params[0] == 'god' and Misc::isGod()) return true;
    return self::_isAllowed($module);
  }
  
  static function _isAllowed($module) {
    // Модуль 'default' по умолчанию разрешен
    if ($module == 'default') return true;
    return in_array($module, self::getAllowedModules());
  }
  
  static protected $allowedAdminModules;
  
  static public function getAllowedModules() {
    if (isset(self::$allowedAdminModules)) return self::$allowedAdminModules;
    self::$allowedAdminModules = Config::getVarVar('adminPriv', 'allowedAdminModules', true);
    return self::$allowedAdminModules;
  }
  
  static function getProperties($name) {
    if (file_exists(LIB_PATH.'/more/admin/'.$name.'/properties.php')) {
      $file = LIB_PATH.'/more/admin/'.$name.'/properties.php';
    } elseif (file_exists(SITE_LIB_PATH.'/more/admin/'.$name.'/properties.php')) {
      $file = SITE_LIB_PATH.'/more/admin/'.$name.'/properties.php';
    } else {
      return false;
    }    
    $props = include $file;
    if (defined('LANG_ADMIN_MODULE_'.$name)) {
      $props['title'] = constant('LANG_ADMIN_MODULE_'.$name);
    }
    return $props;
  }

  static function getProperty($name, $property) {
    $properties = self::getProperties($name);
    return $properties[$property];
  }

  static function sf($name) {
    $s = '';
    if (file_exists(STATIC_PATH.'/js/ngn/admin/'.$name.'.js'))
      $s .= SFLM::getJsTag(STATIC_DIR.'/js/ngn/admin/'.$name.'.js');
    if (file_exists(STATIC_PATH.'/js/ngn/admin/'.$name.'.css'))
      $s .= SFLM::getCssTag(STATIC_DIR.'/js/ngn/admin/'.$name.'.css');
    return $s;
  }
  
}