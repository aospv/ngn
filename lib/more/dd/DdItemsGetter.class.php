<?php

/**
 * Служит только для получения записей без указания конкретного раздела
 */
class DdItemsGetter extends Items {
  
  public $strName;

  public $strData;
  
  public function __construct($strName) {
    $this->strName = $strName;
    parent::__construct(DdCore::table($this->strName));
  }
  
  // ------ Getters ------

  public function getItems() {
    $this->setTStampCond();
    if (!($items = parent::getItems())) return array();
    $this->extendItemsCommon($items);
    $this->extendItemsFilePaths($items);
    $this->extendItemsTags($items);
    $this->extendItemsUsers($items);
    $this->formatItemsText($items);
    $this->extendItems($items);
    return $items;
  }
  
  protected function extendItems(array &$items) {
  }
  
  // --------------------- Варианты кэширования -------------------------

  /*
  public function getItems_cache() {
    $ids = $this->getItemIds();
    $cache = NgnCache::c();
    $items = array();
    foreach ($ids as $id) {
      if (!($item = $cache->load('ddItem'.$id))) {
        $item = $this->getItemF($id);
        $cache->save($item, 'ddItem'.$id);
      }
      $items[$id] = $item;
    }
    return $items;
  }
  
  public function getItems_cache2() {
    $cache = NgnCache::c();
    if (!($items = $cache->load('ddItems'.$this->strName))) {
      $items = $this->getItems_nocache();
      $cache->save($items, 'ddItems'.$this->strName);
    }
    return $items;
  }
  
  public function getItems_cache3() {
    $ids = $this->getItemIds();
    $items = array();
    foreach ($ids as $id) {
      if (($item = Mem::get('ddItem'.$id)) === false) {
        $item = $this->getItemF($id);
        Mem::set('ddItem'.$id, $item);
      }
      $items[$id] = $item;
    }
    return $items;
  }
  */
  
  // -----------------------------------------------------------------------
  
  public function getFirstItem() {
    foreach ($this->getItems() as $v)
      return $v;
    return false;
  }
  
  public function getItem($id) {
    $this->setTStampCond();
    $item = parent::getItem($id);
    $this->extendItemTagIds($item);
    return $item;
  }
  
  protected function extendItem(array &$item) {
  }
  
  public function getItemNonFormat($id) {
    return $this->getItem($id);
  }

  /**
   * Получает отформатированые данные
   *
   * @param   integer   ID записи
   * @return  array     Массив записи
   */
  public function getItemF($id) {
    if (!($item = parent::getItem($id))) return false;
    $this->extendItemFilePaths($item);
    $this->extendItemTags($item);
    $this->extendItemUsers($item);
    $this->extendItemExif($item);
    $this->formatItemText($item);
    $this->extendItem($item);
    return $item;
  }

  public function getItemByField($key, $val) {
    $this->setTStampCond();
    if (!$item = parent::getItemByField($key, $val))
      return false;
    $this->extendItemTags($item);
    $this->extendItemFilePaths($item);
    $this->formatItemText($item);
    $this->extendItem($item);
    return $item;
  }
  
  // ********************************************
  // -------------- Data Exteders ---------------
  // ********************************************
  
  // ------ Tags Extender ------
    
  private function extendItemsTags(&$items) {
    $itemIds = array_keys($items);
    if (!($fields = O::get('DdFields', $this->strName)->getTagFields()))
      return;
    foreach (db()->query("
    SELECT
      tags_items.itemId,
      tags_items.groupName,
      tags_items.collection,
      tags.id,
      tags.title,
      tags.name,
      tags.cnt
    FROM tags_items
    LEFT JOIN tags ON tags_items.tagId=tags.id 
    WHERE
      tags_items.strName=? AND
      tags_items.groupName IN (?a) AND
      tags_items.itemId IN (?a)",
    $this->strName, array_keys($fields), $itemIds) as $v) {
      $tags[$v['itemId']][$v['groupName']][] = array(
        'id' => $v['id'],
        'title' => $v['title'],
        'name' => $v['name'],
        'groupName' => $v['groupName'],
        'collection' => $v['collection'],
        'cnt' => $v['cnt']
      );
    }
    foreach ($fields as $fieldName => $field) {
      foreach ($itemIds as $itemId) {
        if (FieldCore::hasAncestor($field['type'], 'ddTagsSelect')) {
          $items[$itemId][$fieldName] = isset($tags[$itemId][$fieldName]) ?
            $tags[$itemId][$fieldName][0] : array();
        } elseif ($field['type'] == 'tagsTreeMultiselect') {
          // Формируем массив с разбитием на коллекции тэговых записей
          if (isset($tags[$itemId][$fieldName])) {
            $items[$itemId][$fieldName] = array();
            foreach ($tags[$itemId][$fieldName] as $tag) {
              $items[$itemId][$fieldName][$tag['collection']][] = $tag;
            }
          } else {
            $items[$itemId][$fieldName] = array();
          }
        } else {
          $items[$itemId][$fieldName] = 
            DdTagsItems::getItems($this->strName, $fieldName, $itemId);
        }
      }
    }
  }
  
  /**
   * Добавляет данные для тэгов в массив записи
   *
   * @param   array   Массив записи
   */
  private function extendItemTags(&$item) {
    $this->setFieldTagTypes();
    foreach (array_keys($item) as $fieldName) {
      if (
      isset($this->fieldTagTypes[$fieldName]) and 
      ($fieldType = $this->fieldTagTypes[$fieldName])) {
        if (FieldCore::hasAncestor($fieldType, 'ddTagsSelect')) {
          if (($tagItems = DdTagsItems::getItems($this->strName, $fieldName, $item['id']))) {
            $item[$fieldName] = $tagItems[0];
          }
          else $item[$fieldName] = null;
        } elseif ($fieldType == 'tagsTreeMultiselect') {
          $item[$fieldName] = array();
          // Формируем массив с разбитием на коллекции тэговых записей
          foreach (DdTagsItems::getFlat($this->strName, array($fieldName), array($item['id'])) as $tag) {
            $item[$fieldName][$tag['collection']][] = $tag;
          }
        } else {
          $item[$fieldName] = DdTagsItems::getItems($this->strName, 
            $fieldName, $item['id']);
        }
      }
    }
  }
  
  private function extendItemTagIds(&$item) {
    $this->setFieldTagTypes();
    foreach (array_keys($item) as $fieldName) {
      if (isset($this->fieldTagTypes[$fieldName]) and ($fieldType = $this->fieldTagTypes[$fieldName])) {
        if (FieldCore::hasAncestor($fieldType, 'ddTags')) {
          $tags = db()->selectCol('
          SELECT tags.title FROM tags_items, tags
          WHERE
            tags_items.groupName=? AND
            tags_items.strName=? AND
            tags_items.itemId=?d AND
            tags_items.tagId=tags.id
          ', $fieldName, $this->strName, $item['id']);
          $item[$fieldName] = implode(', ', $tags);
          continue;
        }
        elseif (FieldCore::hasAncestor($fieldType, 'ddTagsSelect')) {
          continue;
        }
        elseif (FieldCore::hasAncestor($fieldType, 'ddTagsTreeSelect')) {
          $t = DdTagsItems::getLastTreeItem($this->strName, $fieldName, $item['id']);
          $item[$fieldName] = $t['tagId'];
          continue;
        }
        $tagIds = db()->selectCol('
        SELECT tagId FROM tags_items
        WHERE groupName=? AND strName=? AND itemId=?d
        GROUP BY tagId', $fieldName, $this->strName, $item['id']);
        $item[$fieldName] = $tagIds;
      }
    }
  }
  
  protected $fieldTagTypes;
  
  private function setFieldTagTypes() {
    // Если уже определены ничего не делаем
    if (isset($this->fieldTagTypes)) return;
    $this->fieldTagTypes = Arr::get(
      O::get('DdFields', $this->strName)->getTagFields(), 'type', 'name');
  }
  
  // ------ User Extender ------
  
  private function extendItemsUsers(&$items) {
    foreach (O::get('DdFields', $this->strName)->getFields() as $name => $v)
      if ($v['type'] == 'user') $names[] = $name;
    if (!isset($names)) return;
    foreach ($items as &$item)
      foreach ($names as $name)
        $item[$name] = DbModelCore::get('users', $item[$name]);
  }
  
  private function extendItemUsers(&$item) {
    foreach (O::get('DdFields', $this->strName)->getFields() as $name => $v) {
      if ($v['type'] == 'user') {
        $item[$name] = DbModelCore::get('users', $item[$name]);
      }
    }
  }
  
  // ------ Exif Extender ------
  
  private function extendItemExif(&$item) {
    return;
    /* @var $oF DdFields */
    $oF = O::get('DdFields', $this->strName);
    foreach ($oF->getFieldsByAncestor('image') as $fieldName => $v) {
      $item[$fieldName.'_exif'] = exif_read_data(Misc::getWebFileAbsPath($v));
    }
  }
  
  // ------ File Paths Extender ------

  private function extendItemsFilePaths(&$items) {
    foreach ($items as &$v) {
      $this->extendItemFilePaths($v);
    }
  }

  private function extendItemFilePaths(&$item) {
    /* @var $oF DdFields */
    //$oF = O::get('DdFields', $this->strName);
    $oF = new DdFields($this->strName);
    $types = $oF->getTypes();
    foreach (array_keys($oF->getFileFields()) as $name) {
      if (empty($item[$name]) or !file_exists(UPLOAD_PATH.'/'.$item[$name])) {
        $item[$name] = ''; //$item[$name].' not exists';
        continue;
      } else {
        $item[$name.'_fSize'] = filesize(UPLOAD_PATH.'/'.$item[$name]);
        if (FieldCore::hasAncestor($types[$name], 'image')) {
          $item[$name] = '/'.UPLOAD_DIR.'/'.$item[$name];
          $item['sm_'.$name] = Misc::getFilePrefexedPath($item[$name], 'sm_', 'jpg');
          $item['md_'.$name] = Misc::getFilePrefexedPath($item[$name], 'md_', 'jpg');
        } else {
          $item[$name] = '/'.UPLOAD_DIR.'/'.$item[$name];
        }
      }
    }
  }
  
  // ------ Common Extender ------
  
  private function extendItemsCommon(&$items) {
    $n = 0;
    foreach ($items as &$item) {
      $item['link'] = Tt::getPath(0).'/'.$item['pagePath'].'/'.$item['id'];
      $item['n'] = $n;
      $n++;
    }
  }

  // ------ Text Formatter ------
  
  protected function formatItemsText(&$items) {
    foreach ($items as &$item) {
      $this->formatItemText($item);
    }
  }
  
  protected function formatItemText(&$item) {
    return;
    foreach (O::get('DdFields', $this->strName)->getFields() as $name => $v) {
      if ($v['type'] == 'textarea') {
        $item[$name] = $item[$name.'_f'];
      }
    }
  }
  
  // ------ Timestamp Condition ------

  private function setTStampCond() {
    foreach (array_keys(O::get('DdFields', $this->strName)->getDateFields()) as $fieldName) {
      $this->addSelectCond(
        "UNIX_TIMESTAMP({$this->table}.$fieldName) AS {$fieldName}_tStamp");
    }
  }
  
}
