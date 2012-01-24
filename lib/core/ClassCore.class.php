<?php

class ClassCore {
  
  static public function getAncestors($class) {
    Lib::checkExistance($class);
    for ($classes[] = $class; $class = get_parent_class($class); $classes[] = $class);
    return $classes;
  }
  
  static public function getAncestorsByPrefix($class, $prefix) {
    return array_filter(self::getAncestors($class), function($str) use ($prefix) {
      return Misc::hasPrefix($prefix, $str);
    });
  }
  
  static public function getAncestorNames($class, $prefix) {
    return Arr::filter_empties2(array_map(function($v) use ($prefix) {
      return lcfirst(Misc::removePrefix($prefix, $v));
    }, self::getAncestorsByPrefix($class, $prefix)));
  }
  
  /**
   * Определяет есть ли в классах-предках класса $class класс $ancestor
   *
   * @param   string  Имя класса или объект
   * @param   string  Имя предполагаемого класса предка
   * @param   bool    ..
   */
  static public function hasAncestor($class, $ancestor, $strict = false) {
    if (!$strict and $class == $ancestor) return true;
    if (is_object($class)) $class = get_class($class);
    while (($cl = get_parent_class($class)) !== false) {
      if ($cl == $ancestor) return true;
      $class = $cl;
    }
    return false;
  }
  
  /**
   * Возвращает имена неабстрактных потомков класса.
   * Имя абстрактного класса должно иметь вид: PrefixAbstract
   * Имя неабстрактного класса потомка должно иметь вид: PrefixName
   *
   * @param   string  Имя класса-предка
   *                  Пример: GrabberSourceAbstract
   * @return  array   Имена классов
   */
  static public function getDescendants($ancestorClass, $prefix = false) {
    $classes = array();
    if ($prefix === false) $prefix = str_replace('Abstract', '', $ancestorClass);
    $n = 0;
    foreach (Lib::getClassesListCached() as $class => $v) {
      if ($prefix and !Misc::hasPrefix($prefix, $class)) continue;
      $reflection = new ReflectionClass($class);
      if ($reflection->isAbstract()) continue;
      if (!self::hasAncestor($class, $ancestorClass)) continue;
      $classes[$n] = array(
        'class' => $class,
        'name' => self::classToName($prefix, $class)
      );
      if (self::staticPropertyExists($class, 'title'))
        $classes[$n]['title'] = self::getStaticProperty($class, 'title');
      $n++;
    }
    return $classes;
  }
  
  static public function classToName($prefix, $class) {
    if (is_object($class)) $class = get_class($class);
    return lcfirst(Misc::removePrefix($prefix, $class));
  }
  
  static public function nameToClass($prefix, $name) {
    return $prefix.ucfirst($name);
  }
  
  static public function getObjectsByNames($prefix, array $names) {
    $objects = array();
    foreach ($names as $name) {
      $objects[] = O::get(self::nameToClass($prefix, $name));
    }
    return $objects;
  }
  
  static public function getExistingClass($prefix, array $names) {
    foreach ($names as $name) {
      $class = self::nameToClass($prefix, $name);
      if (O::exists($class)) return $class;
    }
    return false;
  }
  
  static public function getStaticProperties($classPrefix, $prop, $orderProp = null) {
    $properties = array();
    foreach (array_keys(Lib::getClassesListCached()) as $class) {
      if (preg_match('/'.$classPrefix.'(.*)/', $class, $m)) {
        if (!self::staticPropertyExists($class, $prop)) continue;
        if ($orderProp) {
          $properties[$m[1]] = array(
            $prop => self::getStaticProperty($class, $prop),
            $orderProp => self::getStaticProperty($class, $orderProp)
          );
        } else {
          $properties[$m[1]] = self::getStaticProperty($class, $prop);
        }
      }
    }
    if ($orderProp) {
      return Arr::to_options(Arr::sort_by_order_key($properties, $orderProp), $prop);
    }
    return $properties;
  }
  
  static public function getStaticProperty($class, $prop, $strict = true) {
    if (!self::staticPropertyExists($class, $prop)) {
      if ($strict)
        throw new NgnException("Static proprty '$prop' does not exists in class '$class'");
      else
        return false;
    }
    return eval('return '.$class.'::$'.$prop.';');
  }
  
  static public function staticPropertyNotEmpty($class, $prop) {
    if (!class_exists($class)) throw new NgnException("class '$class' does not exists");
    return eval('return !empty('.$class.'::$'.$prop.');');
  }
  
  static public function staticPropertyExists($class, $prop) {
    return eval('return isset('.$class.'::$'.$prop.');');
  }
  
  static public function getStaticPropertyByType($classPrefix, $type, $prop) {
    return self::getStaticProperty($classPrefix.ucfirst($type), $prop);
  }
  
  static public function getClassesByPrefix($prefix) {
    $classes = array();
    foreach (array_keys(Lib::getClassesListCached()) as $class) {
      if (preg_match('/^'.$prefix.'.*/', $class))
        $classes[] = $class;
    }
    return $classes;
  }
  
  static public function getNames($prefix) {
    return array_map(function($class) use ($prefix) {
      return ClassCore::classToName($prefix, $class);
    }, array_filter(
      self::getClassesByPrefix($prefix),
      function($class) {
        $refl = new ReflectionClass($class);
        return !$refl->isAbstract();
      }
    ));
  }

  static public function getParents($class) {
    $pars = array();
    while (($par = get_parent_class($class)) !== false) {
      $pars[] = $par;
      $class = $par;
    }
    return $pars;
  }
  
  static public function checkInstance($obj, $class) {
    if (!is_a($obj, $class))
      throw new NgnException("Class '$obj' must be instance of '$class'");
  }
  
  static public function checkExistance($class) {
    if (!class_exists($class))
      throw new NgnException("Class '$class' does not exists");
  }
  
  static public function clon($obj) {
    return clone $obj;
  }
  
}

