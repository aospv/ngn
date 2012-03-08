<?php

/**
 * Иерархия модулей определяем иерархией классов Pmi*.
 * Например PmiCnd наследуется от PmiPhotoalbum, который в свою очередь
 * является составным модулей 2-х разделов.
 */
class PageModuleCore {

  /**
   * Если модуль не имеет Pmi - значит он виртуальный
   * 
   * @param string $module
   */
  static public function isVirtual($module) {
    return !O::exists(ClassCore::nameToClass('Pmi', Misc::removeSuffix('Slave', $module)));
  }

  static public function getAncestorNames($module, $firstLower = true) {
    $slave = Misc::hasSuffix('Slave', $module);
    if ($slave) $module = Misc::removeSuffix('Slave', $module);
    $class = 'Pmi'.ucfirst($module);
    if (!O::exists($class)) return array($firstLower ? $module : ucfirst($module));
    $r = array_map(
      function($v) use ($firstLower, $slave) {
        $r = str_replace('Pmi', '', $v).($slave ? 'Slave' : '');
        if (!$r) return '';
        return $firstLower ? lcfirst($r) : $r;
      },
      ClassCore::getAncestorsByPrefix($class, 'Pmi')
    );
    return Arr::filter_empties2($r);
  }
  
  static public function getAncestorClasses($module, $prefix) {
    return array_values(array_filter(array_map(
      function($v) use ($prefix) {
        return $prefix.$v;
      },
      self::getAncestorNames($module, false)
    ), function($class) {
      return O::exists($class);
    }));
  }
  
  static public function hasAncestor($module, $ancestorModule) {
    $module = Misc::removeSuffix('Slave', $module);
    return ClassCore::hasAncestor(
      'Pmi'.ucfirst($module), 'Pmi'.ucfirst($ancestorModule));
  }

  /**
   * Возвращает существующий верхний класс из иерархии классов, определенной модулем
   * @param  string  модуль, класс к которому нужно найти
   * @param  string  префикс искомого класса
   */
  static public function getClass($module, $prefix) {
    $classes = self::getAncestorClasses($module, $prefix);
    return isset($classes[0]) ? $classes[0] : false;
  }
  
  static public function action($module, $action, array $options = array()) {
    if (($class = self::getClass($module, 'Pma')) === false) return;
    $o = O::get($class, $options);
    if (!method_exists($o, $action)) return false;
    $o->$action();
  }
  
  static public function getTitle(DbModelPages $page) {
    if (!empty($page['module']) and ($pmi = Pmi::take($page['module'])) !== false) {
      return $pmi->title;
    } else {
      return PageControllersCore::getPropObj($page['controller'])->title;
    }
  }
  
  static public function sf($name, $module) {
    if (empty($module)) return '';
    if (PageModuleCore::isVirtual($module)) return "<!-- Module '$module' is VIRTUAL -->\n";
    $o = new PageModuleSFLM($name, $module);
    return $o->html();
  }
  
  static public function inlineJs(array $d) {
    if (empty($d['page']['module'])) return;
    if (self::isVirtual($d['page']['module'])) return;
    if (($paths = O::get('PageModuleInfo', $d['page']['module'])->
      getFilePaths('inlineJs.php')) === false) return;
    print "\n<!-- inline JS for module '{$d['page']['module']}' -->\n";
    include $paths[0];
  }
  
  static public function getInfo($module) {
    if (!PageModuleCore::isVirtual($module)) {
      $o = new PageModuleInfo($module);
      return $o;
    } else {
      return false;
    }
  }
  
  static public function initPage(DbModelPages $page) {
    if (empty($page->r['settings']['itemTitle']))
      $page->r['settings']['itemTitle'] = 'запись';
  }

}
