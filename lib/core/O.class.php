<?php

/**
 * Класс для подключения библиотек и создания объектов
 *
 */
class O {
  
  static private $storage;
  
  static private function getStoreId($class, $args) {
    for ($i = 1; $i < count($args); $i++) {
      if (is_object($args[$i]) or is_array($args[$i]))
        return false;
      $argsForStoreId[] = $args[$i];
    }
    return $class.(isset($argsForStoreId) ? '_'.implode(',', $argsForStoreId) : '');
  }
  
  static public function create($class) {
    if (!$filepath = Lib::getPath($class))
      throw new NgnException("Class '$class' not found");
    require_once $filepath;
    $args = func_get_args();
    // Необходимо реализовать сохранение в storage тех обектов, чьи
    // параметры можно засеарелизовать в строку не более 255 символов
    for ($i = 1; $i < count($args); $i++)
      $argsStr[] = '$args['.$i.']';
    if (isset($argsStr)) $argsStr = implode(', ', $argsStr);
    eval("\$o = new $class($argsStr);");
    return $o;
  }

  static function exists($path) {
    return Lib::exists($path);
  }
  
  /**
   * Возвращает объект
   *
   * @param   string  Путь до класса без расширения. Пример: "dd/DdItems"
   * @return  mixed
   */
  static public function get($path) {
    $classExists = false;
    if (!strstr($path, '/') and class_exists($path)) {
      $classExists = true;
      $class = $path;
    }
    if (!$classExists) {
      if (!$filepath = Lib::getPath($path))
        throw new NgnException("Class by path '$path' not found");
      $class = preg_replace('/.*\/([\w_]+)\.class\.php/', '$1', $filepath);
    }
    $args = func_get_args();
    // Объекты, в параметрах конструктора которых встречаются массивы или объекты,
    // не могут быть закэшированы
    if (($canStore = $storeId = self::getStoreId($class, $args)) !== false) {
      // Вначале проверяем наличие объекта в ststic-хранилище
      if (isset(self::$storage[$storeId]))
        return self::$storage[$storeId];
    }
    // А если ни там ни там нет, тогда создаем объект
    if (!$classExists) require_once $filepath;
    $reflect = new ReflectionClass($class);
    $args = Arr::get_from($args, 1);
    $obj = $args ? $reflect->newInstanceArgs($args) : $reflect->newInstance();
    if ($canStore) self::$storage[$storeId] = $obj; // Сохраняем в static-хранилище
    return $obj;
  }
  
  static public function take($path) {
    return O::exists($path) ?
      forward_static_call_array(array('O', 'get'), func_get_args()) :
      false;
  }
  
  static function delete($class) {
    $args = func_get_args();
    $storeId = self::getStoreId($class, $args);
    if ($storeId) unset(self::$storage[$storeId]);
  }

}
