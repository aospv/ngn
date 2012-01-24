<?php

class DdTagsItems {

  /**
   * Создает запись тэга
   *
   * @param   string  Имя группы
   * @param   integer ID раздела
   * @param   integer ID записи
   * @param   array   Названия тега
   * @return  mixed   ID в случае успеха или false в случае неудачи
   */
  static function create($strName, $groupName, $itemId, array $titles) {
    if (empty($groupName)) throw new EmptyException('$groupName');
    if (empty($itemId)) throw new EmptyException('$itemId');
    /* @var $oDdTagsGroup DdTagsGroup */
    $oDdTagsGroup = O::get('DdTagsGroup', $strName, $groupName);
    if ($oDdTagsGroup->isTree())
      throw new NgnException("Tag Items of 'Tree' type can not be created by titles");
    // Удаляем текущие ТэгЗаписи
    Arr::filter_empties($titles, false);
    $curItems = self::getItems($strName, $groupName, $itemId);
    self::_delete($strName, $groupName, $itemId);
    /* @var $oDdTagsTagsFlat DdTagsTagsFlat */
    $oDdTagsTagsFlat = O::get('DdTagsTagsFlat', $oDdTagsGroup);
    if ($oDdTagsGroup->itemsDirected) {
      // ТэгЗаписи влияют на Тэги
      // Удаляем те Тэги, заголовков которых нет в будующих ТэгЗаписях
      foreach ($curItems as $v) {
        if (!in_array($v['title'], $titles)) {
          DdTags::deleteById($v['tagId']);
        }
      }
    }
    foreach ($titles as $title) {
      $tag = $oDdTagsTagsFlat->getByTitle($title);
      if (!$tag) {
        if (!$oDdTagsGroup->itemsDirected) // Если ТэгЗаписи не влияют на Тэги
          continue;
        $tagId = $oDdTagsTagsFlat->create(array('title' => $title));
      } else {
        $tagId= $tag['id'];
      }
      self::_create($strName, $groupName, $tagId, $itemId); // Создаем ТэгЗапись
      $cnt = self::updateCount($tagId);
    }
  }
  
  static public function createOrDelete($strName, $groupName, $itemId, $titles) {
    if (empty($titles)) {
      self::delete($strName, $groupName, $itemId);
    } else {
      self::create($strName, $groupName, $itemId, $titles);
    }
  }
  
  // работает как replace
  static public function createByIds($strName, $groupName, $itemId, array $tagIds) {
    self::delete($strName, $groupName, $itemId);
    foreach ($tagIds as $tagId) {
      if (DbModelCore::get('tags', $tagId)) {
        self::_create($strName, $groupName, $tagId, $itemId); // Создаем ТэгЗапись
        self::updateCount($tagId);
      }
    }
  }
  
  static public function createById($strName, $groupName, $itemId, $tagId) {
    self::delete($strName, $groupName, $itemId);
    self::_create($strName, $groupName, $tagId, $itemId);
    self::updateCount($tagId);
  }
  
  // работает как replace
  static public function createByIdsCollection($strName, $groupName, $itemId, $collectionTagIds) {
    self::delete($strName, $groupName, $itemId);
    foreach ($collectionTagIds as $collection => $tagTds) {
      foreach ($tagTds as $tagId) {
        if (DbModelCore::get('tags', $tagId)) {
          self::_create($strName, $groupName, $tagId, $itemId, $collection); // Создаем ТэгЗапись
          self::updateCount($tagId);
        }
      }
    }
  }

  static public function updateCount($tagId) {
    if (self::$disableUpdateCount) return false;
    Misc::checkEmpty($tagId);
    $cnt = db()->selectCell("
    SELECT COUNT(*) FROM
    (SELECT *
      FROM tags_items
      WHERE tagId=?d AND active=1
      GROUP BY itemId) AS t
    ",
    $tagId);
    db()->query('UPDATE tags SET cnt=?d WHERE id=?d', $cnt, $tagId);
    return $cnt;
  }
  
  static public function updateCountByItemId($strName, $itemId) {
    if (self::$disableUpdateCount) return;
    $r = db()->query(
      "SELECT groupName, tagId FROM tags_items WHERE strName=? AND itemId=?d GROUP BY tagId",
      $strName, $itemId);
    foreach ($r as $v) self::updateCount($v['tagId']);
  }

  /**
   * Удаляет те тэги, к которым не было найдено ниодной ТэгЗаписи
   */
  static function cleanup() {
    db()->query('
      SELECT tags.id, tags_items.tagId AS exists FROM tags
      LEFT JOIN tags_items ON tags_items.tagId=tags.id');
  }

  private static function _create($strName, $groupName, $tagId, $itemId, $collection = 0) {
    db()->query(
      'INSERT INTO tags_items SET groupName=?, strName=?, tagId=?d, itemId=?d, collection=?d', 
      $groupName, $strName, $tagId, $itemId, $collection);
  }

  static protected function _delete($strName, $groupName, $itemId) {
    db()->query('DELETE FROM tags_items WHERE strName=? AND groupName=? AND itemId=?d', $strName, 
      $groupName, $itemId);
  }
  
  static public $disableUpdateCount = false;
  
  /**
   * Удаляет все тэг-записи определенной dd-записи в группе,
   * обновляет кол-во записей в тегах
   * 
   * @param  string  Имя структуры
   * @param  string  Имя группы
   * @param  integer ID dd-записи
   */
  static public function delete($strName, $groupName, $itemId) {
    if (empty($groupName)) throw new NgnException('$groupName not defined');
    $tagItems = self::getFlat($strName, array($groupName), array($itemId));
    self::_delete($strName, $groupName, $itemId);
    foreach ($tagItems as $v) {
      if (!isset($v['tagId'])) die2($tagItems);
      self::updateCount($v['tagId']);
    }
  }
  
  /**
   * Удаляет все тег-записи определенного тэга,
   * обновляет кол-во записей в этом тэге
   * 
   * @param  string  Имя структуры
   * @param  string  Имя группы
   * @param  integer ID тэга
   */
  static public function deleteByTagId($strName, $groupName, $tagId) {
    db()->query('DELETE FROM tags_items WHERE strName=? AND groupName=? AND tagId=?d',
      $strName, $groupName, $tagId);
    self::updateCount($tagId);
  }
  
  static public function getFlat($strName, array $groupNames, array $itemIds) {
    $q = '
    SELECT
      tags_items.*,
      tags_items.tagId AS id,
      tags.title,
      tags.name,
      tags.parentId
    FROM tags_items
    LEFT JOIN tags ON tags_items.tagId=tags.id
    WHERE
      tags_items.strName=? AND
      tags_items.groupName IN ('.implode(', ', Arr::quote($groupNames)).') AND
      tags_items.itemId IN ('.implode(', ', $itemIds).') AND
      tags_items.active=1
      ';
    return db()->select($q, $strName);
  }
  
  /**
   * Возвращает тэг-записи, выстроенные в дерево
   *
   * @param   string  Имя структуры
   * @param     array   Имя тэг-группы
   * @param     array   ID dd-записей
   * @return    array   Тэг-записи
   */
  static public function getTree($strName, array $groupNames, array $itemIds) {
    $items = self::getFlat($strName, $groupNames, $itemIds);
    foreach (array_keys($items) as $k) {
      if ($items[$k]['parentId']) {
        unset($items[$k-1]);
        $items[$k] = self::injectInParent($items[$k]['parentId'], $items[$k]);
      }
      
    }
    return array_values($items);
  }
  
  private static $parents;
  
  /**
   * Используется при сохранении древовидных тэгов
   *
   * @param   integer   $parentId
   * @param   integer   $node
   * @return  unknown
   */
  private static function injectInParent($parentId, $node) {
    $parent = DbModelCore::get('tags', $parentId)->r;
    $parent['childNodes'] = array($node);
    self::$parents = $parent;
    if ($parent['parentId'])
      self::injectInParent($parent['parentId'], $parent);
    return self::$parents;
  }
  
  static public function getItems($strName, $groupName, $itemId) {
    /* @var $oDdTagsGroup DdTagsGroup */
    $oDdTagsGroup = O::get('DdTagsGroup', $strName, $groupName);
    if ($oDdTagsGroup->isTree()) {
      return self::getTree($strName, array($groupName), array($itemId));
    } else {
      return self::getFlat($strName, array($groupName), array($itemId));
    }
  }
  
  static public function getLastTreeItem($strName, $groupName, $itemId) {
    if (!($r = self::getTree($strName, array($groupName), array($itemId)))) return;
    list($node) = $r;
    while (1) {
      if (empty($node['childNodes'])) {
        return $node;
      }
      $node = $node['childNodes'][0];
    }
  }
  
  static public function getItemsByIds($strName, $groupName, $itemIds) {
    return db()->query('
    SELECT
      tags.id,
      tags.title,
      tags.groupName,
      tags.name,
      COUNT(*) AS cnt
    FROM tags_items
    LEFT JOIN tags ON tags.id=tags_items.tagId
    WHERE
      tags_items.itemId IN (?a) AND
      tags_items.strName = ? AND
      tags_items.groupName = ?
    GROUP BY tags_items.tagId
    ', $itemIds, $strName, $groupName);
  }
  
  static public $getNonActive = false;
  
  static public function getIdsByName($strName, $groupName, $name) {
    $activeCond = self::$getNonActive ? 'AND tags_items.active=1' : ''; 
    return db()->selectCol("
    SELECT tags_items.itemId FROM
      tags_items,
      tags
    WHERE
      1
      $activeCond
      AND tags.id=?_tags_items.tagId      
      AND tags.strName=?
      AND tags.groupName=?
      AND tags.name=?",
    $strName, $groupName, $name);
  }
  
  static public function getIdsByTagId($strName, $groupName, $tagId) {
    $activeCond = self::$getNonActive ? '' : 'AND active=1'; 
    return db()->selectCol("
    SELECT itemId FROM tags_items
    WHERE strName=? AND groupName=? AND tagId=?d $activeCond",
    $strName, $groupName, $tagId);
  }
  
  static public function getLeafTagIds($strName, $groupName, $itemId) {
    $tagIds = array();
    $dropIds = array();
    foreach (self::getTree($strName, array($groupName), array($itemId)) as $item) {
      if (!empty($item['childNodes'])) {
        $dropIds[] = $item['id'];
        $tagIds = Arr::append($tagIds, self::_getLeftTagIds($item['childNodes']));
      } else {
        $tagIds[] = $item['id'];
      }
    }
    foreach ($dropIds as $id) $tagIds = Arr::drop($tagIds, $id);
    return $tagIds;
  }
  
  static protected function _getLeftTagIds($items) {
    $tagIds = array();
    foreach ($items as $item) {
      if (empty($item['childNodes'])) {
        $tagIds[] = $item['id'];
      } else
        $tagIds = Arr::append($tagIds, self::_getLeftTagIds($item['childNodes']));
    }
    return $tagIds;
  }
  
  static public function activate($strName, $itemId) {
    db()->query("UPDATE tags_items SET active=1 WHERE strName=? AND itemId=?d",
      $strName, $itemId);
    self::updateCountByItemId($strName, $itemId);
  }
  
  static public function deactivate($strName, $itemId) {
    db()->query("UPDATE tags_items SET active=0 WHERE strName=? AND itemId=?d",
      $strName, $itemId);
    self::updateCountByItemId($strName, $itemId);
  }
  
}
