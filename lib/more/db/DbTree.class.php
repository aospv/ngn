<?php

class DbTree implements DbTreeInterface {
  
  public $table;
  
  /**
   * Ключ массива с детьми
   *
   * @var array
   */
  public $childrenKey = 'children';
  
  /**
   * @var DbCond
   */
  public $cond;
  
  public function __construct($table) {
    $this->table = $table;
    $this->cond = new DbCond();
  }
  
  public function getParentId($id) {
    return DbModelCore::get($this->table, $id)->r['parentId'];
  }
  
  public function getChildren($id) {
    return DbModelCore::collection(
      $this->table,
      ClassCore::clon($this->cond)->
        addF('parentId', $id)->
        setOrder('oid')
    );
  }
  
  protected function getTreeR($id) {
    if (!($children = $this->getChildren($id))) return array();
    foreach ($children as $k => $v) {
      if ($v['folder'])
        $children[$k][$this->childrenKey] = $this->getTreeR($v['id']);
    }
    return $children;
  }
  
  /**
   * Возвращает корневой элемент
   */
  public function getRoot() {
    return DbModelCore::get($this->table, 0, 'parentId')->r;
  }
  
  /**
   * Возвращает дерево, начиная с корня
   *
   * @return array
   */
  public function getTree() {
    $this->tree = $this->getRoot();
    $this->tree[$this->childrenKey] = $this->getTreeR($this->tree['id']);
    return $this->tree;
  }
  
  public function getRootTrees() {
    $n = 0;
    $trees = array();
    foreach ($this->getChildren(0) as $node) {
      $trees[$n] = $node;
      $trees[$n][$this->childrenKey] = $this->getTreeR($node['id']);
      $n++;
    }
    return $trees;
  }
  
  protected $parents;
  
  public function getParents($id) {
    $this->parents = array();
    if (($page = DbModelCore::get($this->table, $id)) === false) return false;
    $this->parents[] = $page;
    $this->setParentsR($page['parentId']);
    return $this->parents;
  }

  protected function setParentsR($parentId) {
    if (($page = DbModelCore::get($this->table, $parentId)) === false) return;
    $this->parents[] = $page;
    $this->setParentsR($page['parentId']);
  }
  
  public function getParentsReverse($id) {
    if (($parents = $this->getParents($id)) === false) return false;
    return array_reverse($parents);
  }
  
  public function updatePropertyWithChildren($id, $name, $value) {
    db()->query("UPDATE $this->table SET $name=? WHERE id=?d", $value, $id);
    foreach ($this->getChildren($id) as $v)
      $this->updatePropertyWithChildren($v['id'], $name, $value);
  }
  
}
