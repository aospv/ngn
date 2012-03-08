<?php

class DdItems extends DdItemsGetter {

  public $pageId;

  public $fieldTagTypes;

  // Следующий массив будет определять, тэги из каких полей нужно переностить,
  // а из каких полей нужно удалять при переносе записи
  public $moveActions = array(
    // Тэги из полей этих типов нужно переносить
    'move' => array(
      'tags'
    ), 
    // Тэги из полей этих типов нужно удалять
    'delete' => array(
      'tagsMultiselect'
    )
  );
  
  protected $master = false;
  
  /**
   * @var DbModelPages
   */
  public $page;

  public function __construct($pageId) {
    $this->page = DbModelCore::get('pages', $pageId);
    Misc::checkEmpty($this->page);
    $this->pageId = $this->page['id'];
    parent::__construct($this->page['strName']);
    if (!empty($this->page['settings']['editTime']))
      $this->editTime = $this->page['settings']['editTime'];
    $this->master = PageControllersCore::isMaster($this->page['controller']);
    //$this->addF('pageId', $this->pageId);
  }
  
  /**
   * Получает объект записей по ID записи
   *
   * @param   string  Имя структуры записи
   * @param   integer ID записи
   * @return  DdItems
   */
  static public function getObjByItemId($strName, $itemId) {
    return new self($strName, db()->selectCell(
      'SELECT pageId FROM dd_i_'.$strName.' WHERE id=?d', $itemId));
  }
  
  public $forceDublicateInsertCheck = false;
  
  protected function isDublicateData($hash, $userId) {
    return db()->selectCell("SELECT itemId FROM dd_items WHERE hash=? AND userId=?d",
      $hash, $userId);
  }
  
  public $errors = array();

  public function create(array $data) {
    $data['pageId'] = $this->pageId;
    // Если ID пользователя определен в данных, используем его в качестве 
    // идентификатора пользователя, иначе используем ID сессии 
    $userUnicId = empty($data['userId']) ?
      (isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : '') :
      $data['userId'];
    if (!$this->forceDublicateInsertCheck) {
      // Проверяем на повторное добавление одних и тех же данных
      $hash = hash('ripemd160', serialize($data));
      if ($userUnicId) {
        if ($this->isDublicateData($hash, $userUnicId)) {
          throw new NgnValidError(
            'Форма отправлена второй раз');
        }
      }
    }
    $id = parent::create($data);
    $d = array(
      'itemId' => $id,
      'title' => isset($data['title']) ? $data['title'] : '',
      'strName' => $this->strName,
      'pageId' => $this->pageId,
      'userId' => $userUnicId,
      'hash' => isset($hash) ? $hash : ''
    );
    $d['dateUpdate'] = $d['datePublish'] = $d['dateCreate'] = dbCurTime();
    db()->query('INSERT INTO dd_items SET ?a', $d);
    db()->query('REPLACE INTO comments_active SET parentId=?d, id2=?d, active=?d',
      $this->pageId, $id, isset($data['active']) ? $data['active'] : 1);
      
    $this->clearCache($id);
    return $id;
  }
  
  public function updatePublishDate($id) {
    db()->query("UPDATE {$this->table} SET datePublish=? WHERE id=?d",
      dbCurTime(), $id);
  }
  
  // $data должен иметь весь массив данных структуры
  public function update($id, array $data) {
    parent::update($id, $data);
    // Если обновился title, то меняем его и в dd_titles
    if (isset($data['title']))
      db()->query("UPDATE dd_items SET title=?, dateUpdate=? WHERE itemId=?d AND strName=?",
        isset($data['title']) ? $data['title'] : '',
        dbCurTime(),
        $id, $this->strName);
    $this->clearCache($id);
  }
  
  public function activate($id) {
    parent::activate($id);
    db()->query('UPDATE comments_active SET active=1 WHERE parentId=?d AND id2=?d',
      $this->pageId, $id);
    //DdTagsItems::activate($this->strName, $id);
  }

  public function deactivate($id) {
    parent::deactivate($id);
    //DdTagsItems::deactivate($this->strName, $id);
    db()->query('UPDATE comments_active SET active=0 WHERE parentId=?d AND id2=?d',
      $this->pageId, $id);
  }
  
  public function delete($id) {
    if ($this->master) {
      // Удаляем slave-записи
      $slaveIds = db()->ids(
        DdCore::table(DdCore::getSlaveStrName($this->strName)),
        DbCond::get()->addF(DdCore::masterFieldName, $id)
      );
      $oIM = DdCore::getItemsManager($this->page['settings']['slavePageId']);
      foreach ($slaveIds as $slaveId) $oIM->delete($slaveId);
    }
    PageModuleCore::action($this->page['module'], 'delete', array(
      'oItems' => $this,
      'id' => $id
    ));
    $this->deleteTags($id);
    Comments::deleteByItem($this->pageId, $id);
    parent::delete($id);
    db()->query("DELETE FROM dd_items WHERE itemId=?d AND strName=?",
      $id, $this->strName);    
    db()->query("DELETE FROM notify_subscribe_items WHERE pageId=?d AND itemId=?d",
      $this->pageId, $id);
    db()->query("DELETE FROM grabber_keys WHERE strName=? AND itemId=?d",
      $this->strName, $id);
    $this->clearCache($id);
  }
  
  public function deleteTags($id) {
    foreach (array_keys(O::get('DdFields', $this->strName)->getTagFields()) as $k) {
      DdTagsItems::delete($this->strName, $k, $id);
    }
  }
  
  /**
   * Перемещает записи из раздела в раздел
   *
   * @param   array   ID записей
   * @param   integer ID нового раздела
   */
  public function move($ids, $pageId) {
    $o = new DdStrConverter($this->pageId, $pageId);
    $o->convert($ids);
    foreach ($ids as $id) {
      // Запускаем событие перемещения записи
      $this->event('moveItem', $id);
    }
  }

  private function setItemsCommentCounts(&$items) {
    if (! $items)
      return;
    foreach ($items as $v) $ids[] = $v['id'];
    $counts = db()->select(
    "
    SELECT id2, cnt FROM comments_counts WHERE
      id2 IN (" . implode(',', $ids) . ") AND
      pageId=?d", $this->pageId);
    if (!$counts) return;
    foreach ($counts as $v) $items[$v['id2']]['commentsCount'] = $v['cnt'];
  }
  
  private function moveComments($id, $pageId) {
    $item = $this->getItem($id);
    Comments::move($id, $item['pageId'], $pageId);
  }

  private function moveTags($id, $pageId) {
    $field = O::get('DdFields', $this->strName);
    foreach ($field->getTagFields() as $k => $field) {
      if (in_array($field['type'], $this->moveActions['move'])) {
        // Этот тип поля присутствуем в массиве тех, полей, тэги из которых нужно 
        // переносить вместе с записью
        $item = $this->getItem(
          $id);
        DdTags::moveItems($field['name'], $item['pageId'], $pageId, 
          $id);
      } elseif (in_array($field['type'], $this->moveActions['delete'])) {
        $item = $this->getItem($id);
        DdTags::deleteItems($field['name'], $item['pageId'], $id);
        // Стираем значения для списков
        $this->update($id, 
          array(
            $field['name'] => ''
          ));
      }
    }
  }

  public function shiftUp($id) {
    DbShift::item($id, 'up', $this->table, array(
      'pageId' => $this->pageId
    ));
  }

  public function shiftDown($id) {
    DbShift::item($id, 'down', $this->table, 
      array(
        'pageId' => $this->pageId
      ));
  }

  public function reorderItems($ids) {
    DbShift::items($ids, $this->table);
  }

  ///////////////////////////////////////////////////////
  //////////// Привилегии ///////////////////////////////
  ///////////////////////////////////////////////////////
  
  public $editTime = 9000000000; // много-много

  /**
   * @var PagePriv
   */
  protected $priv;
  
  public function setPriv(PagePriv $priv) {
    $this->priv = $priv;
  }

  public function setActiveCond() {
    if (isset($this->priv['edit'])) return;
    parent::setActiveCond();
  }

  /**
   * @var DdFields
   */
  public $oFields;

  public $fieldTypes;

  ///////////// Calendar /////////////

  public $dateField;

  public function getMonthDaysDataExists($month, $year) {
    if (!isset($this->dateField))
      throw new NgnException('$this->dateField not defined');
    $this->monthsFilterCond = 
      preg_replace('/.*'.$this->dateField.'.*/', '', $this->monthsFilterCond);
    $q = "
        SELECT DAY($this->dateField) AS day FROM {$this->table}
        WHERE
          pageId={$this->pageId} AND
          MONTH($this->dateField)=$month AND
          YEAR($this->dateField)=$year
          $this->monthsFilterCond
        GROUP BY day";
    return db()->selectCol($q);
  }

  public function click($id) {
    db()->query("UPDATE {$this->table} SET clicks=clicks+1 WHERE id=?d", $id);
  }
  
  ////////////// Move from one structure to enother
  
  static public function strMove($pageId, $newStrName) {
    /* @var $oPages Pages */
    $oPages = O::get('Pages');
    $page = DbModelCore::get('pages', $pageId);
    $oldStrName = $page['strName'];
    
    // Проверяем существуют ли колонки с таками же названиями в новой структуре
    $oldFields = db()->fields(DdCore::table($this->strName));
    $newFields = db()->fields(DdCore::table($this->strName));
    
    foreach ($oldFields as $f)
      if (!in_array($f, $newFields))
        throw new NgnException("Поле '$f' отсутствует в новой стуктуре");
        
    $r = db()->select('SELECT * FROM dd_i_'.$oldStrName.' WHERE pageId=?d', $pageId);
    foreach ($r as $v)
      db()->query('INSERT INTO dd_i_'.$newStrName.' SET ?a', $v);
  }
  
  ///////////// Publish //////////////////////////////////
  
  public function publish($id) {
    db()->query(
      "UPDATE {$this->table} SET active=1, datePublish=?d WHERE id=?d",
      dbCurTime());
  }
  
  ///////////// Authors ////////////////
  
  public function getAuthors() {
    $items = db()->select("
    SELECT
      dd.userId,
      u.login,
      UNIX_TIMESTAMP(u.dateCreate) AS dateCreate_tStamp
    FROM {$this->table} AS dd  
    LEFT JOIN users AS u ON u.id = dd.userId
    WHERE
      dd.pageId=?d AND
      u.id > 0
    GROUP BY dd.userId
    $this->orderCond
    $this->limitCond
    ", $this->pageId);
    foreach ($items as &$v) $v += UsersCore::getImageData($v['userId']);
    return $items;
  }
  
  /////////////// Cache /////////////
  
  protected function clearCache($id) {
    DdItemsCacher::cc($this->strName, $id);
  }
  
}
