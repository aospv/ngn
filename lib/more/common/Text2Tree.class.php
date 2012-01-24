<?php

class Text2Tree {
  
  public $text;
  
  public $tab = '- ';
  
  protected $tree;
  
  protected $nodes = array();
  
  public $isRoot = false;
  
  public function setText($text) {
    $this->text = $text;
    $this->tree = null;
    $this->nodes = array();
  }
  
  protected $n = 10;
  
  private function setTree() {    
    if (!$this->text) throw new NgnException('$this->text not defined');
    if ($this->tree) return;
    $lines = explode("\n", $this->text);
    $level = 1;
    foreach ($lines as $line) { 
      preg_match('/((?:'.$this->tab.')+)(.*)/', $line, $m);
      if (count($m) < 3) continue;
      $curLevel = substr_count($m[1], $this->tab);
      $params = $this->parseTitle(trim($m[2]));
      $nodes[$this->n] = array(
        'n' => $this->n,
        'title' => $params['title'],
        'name' => $params['params'][0],
        'module' => $params['params'][0],
        'level' => $curLevel,
      );
      $this->n++;
    }
    if (!isset($nodes)) return;
    $rootLevel = $this->isRoot ? 0 : 1;
    foreach ($nodes as $i => $node) {
      if ($nodes[$i]['level'] == $rootLevel) {
        $parent = NULL;
      } elseif ($nodes[$i]['level'] > $rootLevel) { // Это не корневой уровень
        if ($nodes[$i]['level'] > $nodes[$i-1]['level']) {
          // Текущий уровень больше предыдущего
          $parent = $nodes[$i]['parent'] = $i-1;
        } elseif ($nodes[$i]['level'] < $nodes[$i-1]['level']) {
          // Текущий уровень меньше предыдущего
          // Родитель - это i элемента с уровнем на 1 меньше текущего
          $parent = $nodes[$i]['parent'] = $level2i[$nodes[$i]['level']-1];
        }       
      }
      $level2i[$nodes[$i]['level']] = $i;
      if ($parent) $nodes[$i]['parent'] = $parent;
    }
    // Добавляем ребёнка (текущий узел) в узел, родительский текущему 
    foreach ($nodes as $i => &$node) {
      if (isset($node['parent']))
        $nodes[$node['parent']]['children'][$i] =& $node;
    }
    // Расставляем корневые узлы в дерево
    foreach ($nodes as $i => &$node) {
      if (!isset($node['parent'])) $tree[$i] =& $node;
    }
    $this->nodes = $nodes;
    $this->tree = $tree;
  }
  
  private function parseTitle($title) {
    preg_match('/(.*) \[([^\]]+)\]/', $title, $m);
    if (isset($m[2])) {
      return array(
        'title' => $m[1],
        'params' => Misc::quoted2arr($m[2])
      );
    } else {
      return array(
        'title' => $title,
        'params' => null
      );
    }
  }
  
  public function getNodes() {
    $this->setTree();
    return $this->nodes;
  }
  
  public function &getTree($text = null) {
    if ($text) $this->setText($text);
    $this->setTree();
    return $this->tree;
  }
  
  private function &createNode($nodeData, &$parentNode) {
    if (!$this->tree) throw new NgnException('setTree first');
    $node['n'] = $this->n;
    $node += $nodeData;
    $node['level'] = $parentNode['level']+1;
    $node['parent'] = $parentNode['n'];
    $this->nodes[$parentNode['n']]['children'][$this->n] = $node;
    $this->n++;   
  }
  
  private function &createRoot($nodeData) {
    $node['n'] = $this->n;
    $node += $nodeData;
    $node['level'] = 0;
    $node['parent'] = NULL;
    $this->nodes[$this->n] =& $node;
    $this->tree[$this->n] =& $node;
    $this->n++;
    return $node;
  }
  
  private function moveNode(&$node, &$nodeTo) {
    $node['parent'] = $nodeTo['n'];
    $nodeTo['children'][$node['n']] = $node;
  }
  
  private function removeNodeByN($n) {
    if (!$node =& $this->nodes[$n]) throw new NgnException("Node n=$n not exists");
  }
  
  private function removeNode(&$node) {
    unset($this->nodes[$node['parent']]['children'][$node['n']]);
    if (!count($this->nodes[$node['parent']]['children']))
      unset($this->nodes[$node['parent']]['children']);
  }
  
}
