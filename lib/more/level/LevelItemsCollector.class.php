<?php

class LevelItemsCollector {
  
  /**
   * Текущее время
   *
   * @var timastamp
   */
  protected $now;
  
  /**
   * Время последнего сбора
   *
   * @var timastamp
   */
  protected $last;

  /**
   * Счетчик количества добавленны записей
   *
   * @var integer
   */
  protected $counter = 0;
  
  /**
   * ID раздела информации о пользователе
   *
   * @var integer
   */
  protected $userDataPageId;
  
  public function __construct() {
    if (!($interval = Config::getVarVar('level', 'interval')))
      throw new NgnException('"interval" config can not be empty');
    $this->now = time();
    if (!$this->last = (int)Settings::get('LevelItemsCollectorLastTime'))
      $this->last = $this->now - $interval;
    $this->userDataPageId = db()->selectCell('SELECT id FROM pages WHERE controller=?', 'userData');
  }
  
  public function setNow($time) {
    $this->now = $time;
  }
  
  public function collect() {
    $this->collectDd();
    $this->collectComments();
    Settings::set('LevelItemsCollectorLastTime', time());
    return $this->counter;
  }
  
  protected function collectDd() {
    foreach (db()->ddTables() as $table) {
      $r = db()->query("
      SELECT
        id,
        userId,
        datePublish AS dateCreate
      FROM $table
      WHERE
        datePublish > ? AND
        datePublish < ? AND
        active=1",
      date('Y-m-d H:i:s', $this->last),
      date('Y-m-d H:i:s', $this->now));
      foreach ($r as $v) {
        LogWriter::str('levelsCollect', $table.' = '.$v['id']);
        $v['type'] = 'dd';
        $v['weight'] = 3;
        $v['strName'] = str_replace('dd_i_', '', $table);
        db()->query("REPLACE INTO level_items SET ?a", $v);
        $this->counter++;
      }
    }
  }
  
  protected function collectComments() {
    $r = db()->query("
    SELECT
      comments.id,
      comments.userId,
      comments.dateCreate,
      pages.strName,
      pages.id AS pageId
    FROM comments
    LEFT JOIN pages ON comments.parentId=pages.id
    WHERE
      comments.active=1 AND
      comments.userId IS NOT NULL AND
      pages.active=1 AND
      comments.dateCreate > ? AND
      comments.dateCreate < ?
    ",
    date('Y-m-d H:i:s', $this->last),
    date('Y-m-d H:i:s', $this->now));
    foreach ($r as $v) {
      $v['type'] = 'comments';
      if ($v['pageId'] == $this->userDataPageId) {
        // Для комментариев на странице пользователя вес меньше, чем для всех остальных
        $v['weight'] = 1;
      } else {
        $v['weight'] = 2;
      }
      unset($v['pageId']);
      LogWriter::str('levelsCollect', 'comments. user='.$v['userId']);
      db()->query("REPLACE INTO level_items SET ?a", $v);
      $this->counter++;
    }
  }
    
}