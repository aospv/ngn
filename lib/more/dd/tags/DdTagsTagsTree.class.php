<?php

class DdTagsTagsTree extends DdTagsTagsBase {

  private $curParentIds;

  private $curIds;
  
  private $parentIds;
    
  private $nodes;

  /**
   * Возвращает ID-шники родительских тэгов из указанных тэгов
   *
   * @param   array   ID-шники детей
   * @return  array   Родительские ID-шники, группированые по ID-шникам детей
   *                  Пример:
   *                  getParentIds(array(3, 5))
   *                  вернёт:
   *                  array(
   *                    0 => array(
   *                      51, 34, 3
   *                    ),
   *                    1 => array(
   *                      43, 12, 6
   *                    )
   *                  )
   */
  public function getParentIds(array $ids) {
    $this->curIds = $ids;
    $nodes = $this->getIdsTree();
    foreach ($ids as $id) $this->setParentIds($nodes, $id);
    return array_values($this->parentIds);
  }
  
  public function getParentIds2($id, $includeSelf = true) {
    $this->curIds = array();
    $nodes = $this->getIdsTree();
    $this->setParentIds($nodes, $id);
    $r = Arr::first($this->parentIds);
    if (!$includeSelf) $r = Arr::drop($r, $id);
    return $r;
  }
  
  /**
   * Производит поиск в дереве массива узла с указанным ID и сохраняет в массив $this->parentIds
   * все родительские ID и ID самого узла
   *
   * @param   array     Массив с деревом
   * @param   integer   ID искомого узла
   */
  private function setParentIds(&$nodes, $id) {
    Misc::checkNumeric($id);
    $this->curParentIds = array();
    $this->parentIds[$id] = array();
    $this->_setParentIds($nodes, $id);
    if (empty($this->parentIds[$id]))
      throw new NgnException("Tag ID=$id does not exists in tree");
  }
  
  private function _setParentIds(&$nodes, $id) {
    foreach ($nodes as $node) {
      $this->curParentIds[] = $node['id'];
      if ($node['id'] == $id) {
        $this->parentIds[$id] = Arr::append($this->parentIds[$id], $this->curParentIds);
        return;
      }
      if ($node['childNodes']) {
        $this->_setParentIds($node['childNodes'], $id);
      }
      array_pop($this->curParentIds);
    }
  }
  
  /*
  public function create(array $data) {
    //if (($data = $this->getByTitle($data['title'], $parentId)) !== array()) return $data['id'];
    $data['strName'] = $this->oTG->getStrName();
    $data['groupName'] = $this->oTG->getName();
    DbModelCore::create('tags', $data);
  }
  */
  
  public function getByTitle($title, $parentId) {
    return db()->selectRow(
      'SELECT * FROM tags WHERE strName=? AND groupName=? AND title=? AND parentId=?d', 
      $this->oTG->getStrName(), $this->oTG->getName(), $title, $parentId);
  }

  public function getTree() {
    return db()->select(
    "
    SELECT
      id,
      parentId,
      title,
      name,
      groupName,
      cnt,
      id       AS ARRAY_KEY, 
      parentId AS PARENT_KEY
    FROM tags".$this->getCond()->all());
    
  }
  
  public function getIdsTree() {
    return db()->select(
    "
    SELECT
      id,
      id       AS ARRAY_KEY, 
      parentId AS PARENT_KEY
    FROM tags
    WHERE
      strName=? AND
      groupName=?
    ORDER BY oid
    LIMIT 500
    ",
    $this->oTG->getStrName(), $this->oTG->getName());
  }
  
  public function getTags($parentId) {
    return db()->select(
    "
    SELECT
      id,
      parentId,
      title,
      cnt
    FROM tags
    WHERE
      strName=? AND
      groupName=? AND
      parentId=?d
    ORDER BY oid
    ",
    $this->oTG->getStrName(), $this->oTG->getName(), $parentId);
  }
  
  public function import($text) {
    $oT2T = new Text2Tree();
    $oT2T->setText($text);
    $ids = array();
    $n = 10;
    foreach ($oT2T->getNodes() as $v) {
      $parent = (isset($v['parent']) and isset($ids[$v['parent']])) ?
        $ids[$v['parent']] : 0;
      $id = $this->create(array(
        'title' => $v['title'],
        'parentId' => $parent,
        'oid' => $n
      ));
      $ids[$v['n']] = $id;
      $n += 10;
    }
  }
  
  public function getData() {
    return $this->getTree();
  }
  
  // ---------------------------
  
  protected $branchNodes;
  
  public function getBranchFromRoot($childId) {
    $tree = $this->getTree();
    foreach ($tree as $node) {
      if ($node['id'] == $childId) return $this->getWithoutChildren($node);
      if (!empty($node['childNodes'])) {
        $this->branchNodes = array($this->getWithoutChildren($node));
        if ($this->processBranch($node['childNodes'], $childId)) {
          for ($i=1; $i<count($this->branchNodes); $i++) {
            $this->branchNodes[$i-1]['childNodes'][] =& $this->branchNodes[$i];
          }
          return $this->branchNodes[0];
        }
      }
    }
    return false;
  }
  
  protected function getWithoutChildren(array $node) {
    $node['childNodes'] = array();
    return $node;
  }
  
  protected function processBranch(array $nodes, $childId) {
    foreach ($nodes as $node) {
      if ($node['id'] == $childId) {
        $this->branchNodes[] = $this->getWithoutChildren($node);
        return true;
      }
      if (!empty($node['childNodes'])) {
        $this->branchNodes[] = $this->getWithoutChildren($node);
        if ($this->processBranch($node['childNodes'], $childId)) return true;
        array_pop($this->branchNodes);
      }
    }
    return false;
  }
   
  // ---------------------------
  
  protected function getChildrenIds($parentId, $includeParent = true) {
    $node = $this->findNode($parentId, $this->getIdsTree());
    $ids = $includeParent ? array($parentId) : array();
    return Arr::append($ids, TreeCommon::getFlatParams($node['childNodes'], 'id'));
  }
  
  protected function findNodeAndRemoveChildren($id, &$nodes) {
    foreach ($nodes as &$node) {
      if ($node['id'] == $id) {
        $node['childNodes'] = array();
        return true;
      }
      elseif (
      !empty($node['childNodes']) and 
      $this->findNodeAndRemoveChildren($id, $node['childNodes'])
      ) {
        return true;
      }
    }
    return false;
  }
  
  protected function findNode($id, $nodes) {
    foreach ($nodes as $node) {
      if ($node['id'] == $id) return $node;
      elseif (!empty($node['childNodes'])) {
        if (($_node = $this->findNode($id, $node['childNodes'])) !== false) {
          return $_node;
        }
      }
    }
    return false;
  }

  /**
   * @param integer Кого
   * @param integer Куда
   * @param integer После кого
   */
  public function move($id, $toId, $where = 'after') {
    $strName = $this->oTG->getStrName();
    $groupName = $this->oTG->getName();    
    $oldParentTagIds = $this->getParentIds2($id, false);
    MifTree::move($this, 'tags', $id, $toId, $where, 
      array(
        'strName' => $strName, 
        'groupName' => $groupName
      )
    );
    $newParentTagIds = $this->getParentIds2($id, false);
    $tagIdsToDelete = array_diff($oldParentTagIds, $newParentTagIds);
    foreach ($tagIdsToDelete as $tagId) {
      DdTagsItems::deleteByTagId($strName, $groupName, $tagId);
    }
  }
  
}
