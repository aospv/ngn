<?php

class PagesPathUpdater {

  public $firstPathLevel = 0;
  
  protected $tree;
  
  public function __construct(DbTree $oDbTree) {
    $this->tree = $oDbTree;
  }

  protected function updateNode($id) {
    if (($nodes = $this->tree->getParentsReverse($id)) === false) return;
    if (!$nodes[count($nodes) - 1]['path']) {
      $nodes[count($nodes) - 1]['path'] = $nodes[count($nodes) - 1]['name'];
    }
    $level = 0;
    foreach ($nodes as $node) {
      if ($level >= $this->firstPathLevel) {
        $items[] = array(
          'title' => $node['title'], 
          'link' => '/'.$node['path'], 
          'name' => $node['name'], 
          'id' => $node['id'], 
          'folder' => $node['folder']
        );
      }
      if (count($nodes) == 1)
        $path[] = $node['name'];
      elseif ($level >= 1)
        $path[] = $node['name'];
      $pids[] = $node['parentId'];
      $level++;
    }
    $path = implode(PAGE_PATH_SEP, $path);
    $pids = implode(',', $pids); 
    $items[count($items)-1]['link'] = '/'.$path;
    $items = !empty($items) ? serialize($items) : '';
    db()->query("UPDATE {$this->tree->table} SET pids=?, path=?, pathData=? WHERE id=?d",
      $pids, $path, $items, $id);
    DbModelCore::cc($this->tree->table, $id);
  }
  
  public function update($id) {
    $this->updateNode($id);
    if (($nodes = $this->tree->getChildren($id))) {
      foreach ($nodes as $v) {
        $this->updateNode($v['id']);
      }
    }
  }

}