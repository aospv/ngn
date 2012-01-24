<?php

class MifTree {
  
  protected $childrenKey = 'childNodes';
  protected $allowedDataParams = array('id'/*, 'title'*/);
  protected $data;
  
  public function node(array $data = array()) {
    $node = array(
      'property' => array(
        'id' => $data['id'], // необходимо для StateStorage'а
        'name' => $data['title'] //. " ({$data['id']})",
      )
    );
    $this->setNodeType($node, $data);
    $this->setNodeCls($node, $data);
    if ($data) {
      foreach ($data as $k => $v) {
        if (!in_array($k, $this->allowedDataParams)) continue;
        $node['data'][$k] = $v;
      }
    }
    return $node;
  }
  
  protected function setNodeType(array &$node, array $data) {
    $node['type'] = !empty($data[$this->childrenKey]) ? 'folder' : 'page';
  }
  
  protected function setNodeCls(array &$node, array $data) {
  }
  
  public function setData(array $data) {
    $this->data = $data;
    return $this;
  }
  
  public function getTree($forest = true) {
    $root = $this->node(array(
      'id' => 0,
      'title' => 'root'
    ));
    $this->setChildren($root, $this->data);
    return $forest ? $root['children'] : $root;
  }

  /**
   * @param   array   Массив, в который будут записаны данные для узла в JSON
   * @param   array   Массив с исходными данными узлов
   */
  public function setChildren(array &$node, array $nodesData) {
    $n = 0;
    foreach ($nodesData as $v) {
      if (empty($v['title'])) $v['title'] = '{empty}';
      $children[$n] = $this->node($v);
      if (!empty($v[$this->childrenKey])) {
        $this->setChildren($children[$n], $v[$this->childrenKey]);
      }
      $n++;
    }
    $node['children'] = isset($children) ? $children : array();
  }
  
  private function addChildren(array &$node, array &$data) {
    $node['children'][] = $this->node($data['title']);
  }
  
  //////////////////// Database Functions //////////////////////
  
  static public function move(DbTreeInterface $oTree, $table, $id, $toId, $where = 'after', $whereParams = null) {
    if ($where == 'inside') {
      $parentId = $toId;
      $whereParams['parentId'] = $parentId;
      // Получаем последний OID
      $oid = db()->selectCell("
        SELECT oid FROM $table 
        WHERE ".db()->getAndCond($whereParams)." 
        ORDER BY oid DESC
        LIMIT 1");
      $oid++;
    } elseif ($where == 'before') {
      $parentId = $oTree->getParentId($toId);
      $oid = db()->selectCell("SELECT oid FROM $table WHERE id=?d", $toId);
      $oid--;
    } else {
      $parentId = $oTree->getParentId($id);
      $oid = db()->selectCell("SELECT oid FROM $table WHERE id=?d", $toId);
      $oid++;
    }
    db()->query("UPDATE $table SET parentId=?d WHERE id=?d", $parentId, $id);
    db()->query("UPDATE $table SET oid=?d WHERE id=?d", $oid, $id);
    $whereParams['parentId'] = $parentId;
    DbShift::sort2($table, $whereParams);
  }
  
}
