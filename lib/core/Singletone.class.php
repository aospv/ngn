<?php

abstract class Singletone {

  private static $instances;

  public function __construct() {
    $c = get_class($this);
    if(isset(self::$instances[$c])) {
      throw new Exception('You can not create more than one copy of a singleton.');
    } else {
      self::$instances[$c] = $this;
    }
  }

  static public function getInstance() {
    $c = get_called_class();
    if (!isset(self::$instances[$c])) {
      $args = func_get_args();
      $reflectionObject = new ReflectionClass($c);
      self::$instances[$c] = $reflectionObject->newInstanceArgs($args);
    }
    return self::$instances[$c];
  }

  public function getName() {
    return get_class($this);
  }

  public function __clone() {
    throw new Exception('You can not clone a singleton.');
  }

}
