<?php

class DdTags {
  
  static public function isTagType($type) {
    if (($r = DdFieldCore::getTypeData($type, false)) === false) return false;
    return array_key_exists('tags', $r);
  }
  
  static public function isTagTreeType($type) {
    return array_key_exists('tagsTree', DdFieldCore::getTypeData($type));
  }
  
  static public function isTagItemsDirectedType($type) {
    return array_key_exists('tagsItemsDirected', DdFieldCore::getTypeData($type));
  }
  
  static public function getLink($path, array $tag, $id = false) {
    return $path.'/t2.'.$tag['groupName'].'.'.$tag['id'];
  }
  
  /**
   * @param   string   Structure string name
   * @param   string   Tags group name
   * @return  DdTagsTagsBase
   */
  static public function get($strName, $groupName) {
    $oTG = O::get('DdTagsGroup', $strName, $groupName);
    return $oTG->isTree() ?
      O::get('DdTagsTagsTree', $oTG) : O::get('DdTagsTagsFlat', $oTG);
  }

  /**
   * @param   integer   Tags group ID
   * @return  DdTagsTagsBase
   */
  static public function getByGroupId($groupId) {
    $oTG = DdTagsGroup::getObjById($groupId);
    return $oTG->isTree() ? new DdTagsTagsTree($oTG) : new DdTagsTagsFlat($oTG);
  }

  static public function title2name($title) {
    return trim(Misc::translate($title, true), '-');
  }
  
  static public function getById($id) {
    return db()->selectRow('SELECT * FROM tags WHERE id=?d', $id);
  }

  static public function getTagsByGroup($strName, $groupName) {
    $r = db()->query(
      'SELECT id, title FROM tags WHERE strName=? AND groupName=? ORDER BY oid', 
      $strName, $groupName);
    foreach ($r as &$v) {
      $v['name'] = DdTags::title2name($v['title']);
    }
    return $r;
  }

  static public function deleteById($id) {
    DbModelCore::delete('tags', $id);
    db()->query('DELETE FROM tags_items WHERE tagId=?d', $id);
    // Убиваем детей
    foreach (db()->ids('tags', DbCond::get()->addF('parentId', $id)) as $childId)
      self::deleteById($childId);
  }
  
  static public function rebuildCounts() {
    db()->select('UPDATE tags SET cnt=0');
    foreach ((db()->select(
      '
    SELECT strName, groupName, tagId AS id, COUNT(*) AS cnt
    FROM tags_items GROUP BY strName, groupName, tagId')) as $v) {
      db()->select(
        '
      UPDATE tags SET cnt=?d WHERE strName=? AND groupName=? AND id=?d', $v['cnt'], 
        $v['strName'], $v['groupName'], $v['id']);
    }
  }
  
  static public function rebuildNames() {
    foreach (db()->query('SELECT id, title FROM tags') as $v) {
      db()->query('UPDATE tags SET name=? WHERE id=?d', DdTags::title2name($v['title']), $v['id']);
    }
  }
  
  /**
   * Обнуляет несуществующие parentId
   */
  static public function rebuildParents() {
    foreach (db()->select('
    SELECT
      tags.strName,
      tags.groupName,
      tags.id,
      tags.parentId
    FROM tags
    LEFT JOIN tags_groups ON
      tags_groups.strName=tags.strName AND
      tags_groups.name=tags.groupName
    WHERE tags_groups.tree=1') as $v) {
      $ids[$v['strName']][$v['groupName']][] = $v['id'];
      if ($v['parentId'])
        $parentIds[$v['strName']][$v['groupName']][] = $v['parentId'];
    }
    foreach ($parentIds as $strName => $v1) {
      foreach ($v1 as $groupName => $v2) {
        foreach ($v2 as $parentId) {
          if (!in_array($parentId, $ids[$strName][$groupName]))
            db()->query('
            UPDATE tags SET parentId=0
            WHERE parentId=?d AND strName=? AND groupName=?',
              $parentId, $strName, $groupName);
        }
      }
    }
  }
  
}