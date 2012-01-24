<?php

class PagesAdmin extends PagesTree {

  public $firstPathLevel = 0;

  public function getChildren($id) {
    $items = parent::getChildren($id);
    foreach ($items as &$v) {
      $v['settings'] = unserialize($v['settings']);
      if (!empty($v['controller'])) {
        $v['editebleContent'] = PageControllersCore::isEditebleContent($v['controller']);
      }
    }
    return $items;
  }
  
  public function move($pageId, $toPageId) {
    if (in_array($pageId, $this->getParentsIds($toPageId)))
      return; // Нельзя перемещать в детей
    db()->query("UPDATE pages SET parentId=?d WHERE id=?d", $toPageId, $pageId);
    $this->updatePath($pageId);
    NgnCache::cleanTag('pages');
  }
  
  public function moveMif($id, $toId, $where) {
    MifTree::move($this, 'pages', $id, $toId, $where);
    $nodeAfter = Pages::getNode($id);
    $this->updateFolderStatus($nodeAfter['parentId']);
    $this->updatePath($id);
    NgnCache::cleanTag('pages');
  }
  
  protected function updatePathNode($id) {
    if (! $pages = $this->getParentsReverse($id)) {
      return;
    }
    // Если у раздела нет родетелей, считаем первый уровень для пути на единицу меньше
    //if (count($pages) == 1) $this->firstPathLevel = 0;
    if (!$pages[count($pages) - 1]['path']) {
      $pages[count($pages) - 1]['path'] = $pages[count($pages) - 1]['name'];
    }
    $level = 0;
    foreach ($pages as $page) {
      if ($level >= $this->firstPathLevel) {
        $items[] = array(
          'title' => $page['title'], 
          'link' => '/'.$page['path'], 
          'name' => $page['name'], 
          'id' => $page['id'], 
          'folder' => $page['folder']
        );
      }
      if (count($pages) == 1)
        $path[] = $page['name'];
      elseif ($level >= 1)
        $path[] = $page['name'];
      $pids[] = $page['parentId'];
      $level++;
    }
    $path = implode(PAGE_PATH_SEP, $path);
    $pids = implode(',', $pids); 
    $items[count($items)-1]['link'] = '/'.$path;
    $items = !empty($items) ? serialize($items) : '';
    db()->query("UPDATE pages SET pids=?, path=?, pathData=? WHERE id=?d",
      $pids, $path, $items, $id);
  }

  public function updatePath($id) {
    $this->updatePathNode($id);
    if (($pages = $this->getChildren($id))) {
      foreach ($pages as $page) {
        $this->updatePath($page['id']);
      }
    }
  }

  public function updateTitle($id, $title) {
    parent::updateTitle($id, $title);
    $this->updatePath($id);
    NgnCache::cleanTag('pages');
  }

  /**
   * Добавляет массив с настройками к уже существующим, или создаёт заново
   *
   * @param integer ID раздела
   * @param array   Исходный массив настроек
   */
  public function addSettings($id, $settings) {
    $initSettings = $this->getInitSettings($id);
    foreach ($settings as $k => $v) $initSettings[$k] = $v;
    $this->updateSettings($id, $initSettings, true);
  }
  
  public function replaceSettings($id, $initSettings) {
    $this->updateSettings($id, $initSettings, false);
  }
  
  private function updateSettings($id, $initSettings, $isAdd = true) {
    if (!($page = $this->getNode($id)))
      throw new NgnException("Page ID=$id does not exists");
    // Если модуль определен, запускаем экшн для формирования его настроек
    if (!empty($page['controller'])) {
      $_settings = PageControllersCore::settingsAction($page['controller'], $initSettings);
    } else {
      $_settings = $initSettings;
    }
    
    // $_settings - cформированые настройки
    $settings = $page['settings'];
    
    // Если существуют текущие настройки и сформированые
    if ($isAdd and $settings and $_settings)
      $settings = array_merge($settings, $_settings);
    else
      $settings = $_settings;
      
    // Если initSettings уже существуют
    if ($isAdd and $page['initSettings'])
      foreach ($page['initSettings'] as $k => $v)
        if (!isset($initSettings[$k])) $initSettings[$k] = $v;
      
    db()->query(
      "UPDATE pages SET strName=?, mysite=?, initSettings=?, settings=? WHERE id=?d", 
      isset($settings['strName']) ? $settings['strName'] : '',
      isset($settings['mysite']) ? $settings['mysite'] : 0,
      serialize($initSettings),
      serialize($settings), 
      $id
    );
  }
  
  private function updateSettingsDirect($id, $settings) {
    db()->query('UPDATE pages SET settings=?, initSettings=? WHERE id=?d',
      serialize($settings), serialize($settings), $id);
  }

  public function updateSettingsValue($id, $k, $v) {
    $settings = $this->getSettings($id);
    $settings[$k] = $v;
    $this->updateSettingsDirect($id, $settings);
  }

  static function smallSizesChanges(&$oldSettings, &$curSettings) {
    if ($oldSettings['smW'] != $curSettings['smW'])
      return true;
    if ($oldSettings['smH'] != $curSettings['smH'])
      return true;
    return false;
  }

  static function middleSizesChanges(&$oldSettings, &$curSettings) {
    if ($oldSettings['mdW'] != $curSettings['mdW'])
      return true;
    if ($oldSettings['mdH'] != $curSettings['mdH'])
      return true;
    return false;
  }

  public function getParents($id) {
    $this->parents = array();
    if (!($page = $this->getNode($id))) return false;
    $this->parents[] = $page;
    $this->setParentsR($this->parents[0]['parentId']);
    return $this->parents;
  }

  public function getParentsIds($id) {
    $this->parents = array();
    $this->setParentsR($id);
    $ids = array();
    foreach ($this->parents as $v)
      $ids[] = $v['id'];
    return $ids;
  }

  public function getAllChildrenIds($id) {
    if (($ids = db()->selectCol("SELECT id FROM pages WHERE parentId=?d", $id))) {
      foreach ($ids as $_id) {
        $ids += $this->getAllChildrenIds($_id);
      }
    }
    return $ids;
  }

  public function getParentsReverse($id) {
    if (!$parents = $this->getParents($id))
      return false;
    return array_reverse($parents);
  }

  public function setParentsR($parentId) {
    if (($parent = $this->getNode($parentId))) {
      $this->parents[] = $parent;
      $this->setParentsR($parent['parentId']);
    }
  }
  
  public function _delete($id) {
    if (!$id) return; // нельзя позволять. т.к. $this->getChildren(0) вернут корневые разделы
    db()->query('DELETE FROM pages WHERE id=?d', $id);
    foreach (db()->selectCol("SELECT id FROM pages WHERE parentId=?d", $id) as $childId) {
      $this->_delete($childId);
    }
    PageBlockCore::deleteByPageId($id);
    Slice::deleteByPageId($id);
    db()->query("DELETE FROM notify_subscribe_items WHERE pageId=?d", $id);
    db()->query("DELETE FROM notify_subscribe_pages WHERE pageId=?d", $id);
  }

  public function delete($id) {
    $this->_delete($id);
    // Подчищаем привилегии этого раздела
    O::get('PrivilegesManager')->cleanup();
    // Удаляем запись из "Избранных"
    Settings::remove('adminPagesFavorits', $id);
    NgnCache::cleanTag('pages');
  }

  public function activate($id) {
    $this->_activate($id);
    NgnCache::cleanTag('pages');
  }
  
  private function _activate($id) {
    db()->query("UPDATE pages SET active=1 WHERE id=?d", $id);
    foreach ($this->getChildren($id) as $child) {
      $this->_activate($child['id']);
    }
  }

  public function deactivate($id) {
    $this->_deactivate($id);
    NgnCache::cleanTag('pages');
  }
  
  public function _deactivate($id) {
    db()->query("UPDATE pages SET active=0 WHERE id=?d", $id);
    foreach ($this->getChildren($id) as $child) {
      $this->_deactivate($child['id']);
    }
  }
  
  public function beforeUpdateAction($id, &$data) {
    $data['id'] = $id;
    return;
    // по умолчанию конечные настройки равны исходным
    if (isset($data['initSettings']))
      $data['settings'] = $data['initSettings'];
      // Для каждого плагина могут быть определены свои правила предобработки данных.
    // Где-то необходимо производить дополнительные операции при добавлении/изменении раздела
    // Где-то необходимо обрабатывать исходные настройки (initSettings) и преобразовывать 
    // из в конечные (settings)
    //Lib::required('more/module/Module.class.php');
    //PageControllersCore::action($data['controller'], $data);    
    if (isset($data['settings']))
      $data['settings'] = serialize($data['settings']);
    if (isset($data['initSettings']))
      $data['initSettings'] = serialize($data['initSettings']);
    return;
  }
  
  // ..................................................................
  
  public function isSlave($id) {
    $node = $this->getNode($id);
    return (bool)$node['slave'];
  }

}

