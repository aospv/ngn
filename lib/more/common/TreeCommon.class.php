<?php

class TreeCommon {

  static public function getFlatParams(array $tree, $param) {
    $params = array();
    foreach ($tree as $node) {
      $params[] = $node[$param];
      if (!empty($node['childNodes']))
        $params = Arr::append($params, self::getFlatParams($node['childNodes'], $param));
    }
    return $params;
  }
  
  static public function getFlatDddd(array $tree, $dddd) {
    $params = array();
    foreach ($tree as $node) {
      $params[] = St::dddd($dddd, $node);
      if (!empty($node['childNodes']))
        $params = Arr::append($params, self::getFlatDddd($node['childNodes'], $dddd));
    }
    return $params;
  }
  
  static protected $id;
  static protected $parentId;
  static public $idName = 'n';
  static public $parentIdName = 'parent';
  static protected $result;
  
  static public function getFlatAddParentIds(array $tree) {
    self::$parentId = 0;
    self::$id = 0;
    self::$result = array();
    self::setFlatAddParentIds($tree);
    return self::$result;
  }
  
  static protected function setFlatAddParentIds(array $nodes) {
    foreach ($nodes as $v) {
      self::$id++;
      $v[self::$parentIdName] = self::$parentId;
      $v[self::$idName] = self::$id;
      self::$result[] = $v;
      if (!empty($v['children'])) {
        $parentId = self::$parentId;
        self::$parentId = self::$id;
        self::setFlatAddParentIds($v['children']);
        self::$parentId = $parentId;
      }
    }
  }
  
}
