<?php

class DbModelPages extends DbModel {

  public function getModule() {
    return empty($this->r['module']) ? $this->r['controller'] : $this->r['module'];
  }
  
  public function getS($key) {
    return isset($this->r['settings'][$key]) ? $this->r['settings'][$key] : false;
  }
  
  static public $serializeble = array(
    'pathData', 'initSettings', 'settings'
  );
  
  static public function unpack(array &$r) {
    parent::unpack($r);
    $r['settings'] = array_merge(
      PageControllersCore::getDefaultSettings($r['controller']), (array)$r['settings']
    );
  }
  
  protected function init() {
    PageModuleCore::initPage($this);
  }
  
  static public function getHomepage() {
    return DbModelCore::get('pages', 1, 'home');
  }
  
  static public function _update($id, array $data, $filterByFields = false) {
    self::check($data);
    if (!empty($data['settings']['strName']))
      $data['strName'] = $data['settings']['strName'];
    $page = DbModelCore::get('pages', $id);
    if (!empty($data['settings']) and !empty($page['controller'])) {
      $data['settings'] = PageControllersCore::settingsAction(
        $page,
        $data['settings']
      );
      $data['initSettings'] = $data['settings'];
    }
    if ($page->getModule() and !empty($data['module']) and $page['module'] != $data['module'])
      O::get('DdoSettings', $page->getModule())->rename($data['module']);
    parent::update('pages', $id, $data, $filterByFields);
    if (!empty($data['name'])) self::updatePath($id);
  }
  
  static public function addSettings($id, array $settings) {
    DbModelCore::update('pages', $id, array(
      'settings' => array_merge(
        DbModelCore::get('pages', $id)->r['settings'] ?: array(),
        $settings
      )
    ));
  }
  
  static function updateStrName($old, $new) {
    foreach (DbModelCore::collection('pages') as $v) {
      if (isset($v['settings']['strName']) and $v['settings']['strName'] == $old) {
        $s['strName'] = $new;
        DbModelCore::update('pages', $v['id'], array('strName' => $new));
      }
    }
  }
  
  static public $defaultCreateValues = array(
    'parentId' => 0,
    'active' => 1
  );
  
  static public function _create(array $data, $filterByFields = false) {
    self::check($data);
    self::addDefaultUpdateData($data);
    $id = db()->query('INSERT INTO pages SET ?a', array('title' => 'dummy'));
    $last = Arr::last(self::getTree()->getChildren($data['parentId']));
    $data['oid'] = $last['oid'] + 10;
    self::_update($id, $data, $filterByFields);
    return $id;
  }
  
  static protected function check(array $data) {
    if (!empty($data['name']) and self::isReserved($data['name']))
      throw new NgnException("Name {$data['name']} is reserved");
  }

  /**
   * Проверяет на наличие имени страницы в списке зарезервированых слов
   *
   * @param   string    Имя страницы
   */
  static public function isReserved($name) {
    if (preg_match('/pg(\d)/', $name)) return false; // Pagination
    return in_array($name, Config::getVar('reservedPageNames'));
  }
  
  static public function searchPage($mask, $extraCond = '') {
    $mask = $mask.'%';
    if ($extraCond) $extraCond = ' AND '.$extraCond;
    foreach (db()->select("
      SELECT
        pages.id AS ARRAY_KEY,
        pages.title,
        pages2.title AS title2
      FROM pages
      LEFT JOIN pages AS pages2 ON pages.parentId=pages2.id
      WHERE pages.title LIKE ?
        $extraCond
      LIMIT 10", $mask) as $k => $v) {
      $pages[$k] = $v['title'].($v['title2'] ? ' ← '.$v['title2'] : '');
    }
    return $pages;
  }
  
  static public function searchFolder($mask) {
    return self::searchPage($mask, 'pages.folder=1');
  }

  static public function getTree() {
    return new DbTree('pages');
  }
  
  static public function updatePath($id) {
    O::get('PagesPathUpdater', self::getTree())->update($id);
  }
  
  static public function move($id, $toId, $where) {
    MifTree::move(self::getTree(), 'pages', $id, $toId, $where);
    DbModelPages::updatePath($id);
  }
  
}
