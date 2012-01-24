<?php

/**
 * Класс отвечает за назначение уровней
 * 
 * Что он делает.
 * 1. Собирает комментарии и записи пользователей за интервал времени
 * 2. Назначает уровень тем пользователям, что набрали достаточное кол-во записей
 * 
 * интервал времени - периодичность назначения уровней
 */
abstract class LevelNominator {

  /**
   * Интервал за который собирается кол-во записей необходимых для получения этого уровня
   *
   * @var integer
   */
  protected $interval;
  
  protected $now;
  
  protected $level = 1;
  
  protected $condition = 'or';
  
  protected $requirements = array(
    'dd' => 1,
    'comments' => 5
  );
  
  protected $nominateUserIds = array();
  
  protected $nominatedUserIds = array();
  
  public function __construct() {
    $this->now = time();
    $this->interval = Config::getVarVar('level', 'interval');
  }
  
  public function setNow($time) {
    $this->now = $time;
  }
  
  public function process() {
    $this->nominateLevel();
  }
  
  protected function nominateLevel() {
    $this->condition == 'or' ?
      $this->addNominateUsersOr() :
      $this->addNominateUsersAnd();
    if (!count($this->nominateUserIds))
      return;
    $curLevels = db()->selectCol(
      'SELECT userId AS ARRAY_KEY, level FROM level_users WHERE userId IN (?a)',
      $this->nominateUserIds);
    foreach ($this->nominateUserIds as $userId) {
      if ($curLevels[$userId] >= $this->level)
        continue;
      db()->query('REPLACE INTO level_users SET userId=?d, level=?d, nominateDate=?',
        $userId, $this->level, dbCurTime());
      $this->nominatedUserIds[] = $userId;
    }
    
    /*    
    
    $curLevels = db()->select("
    SELECT
      userId AS ARRAY_KEY,
      level,
      UNIX_TIMESTAMP(nominateDate) AS nominateDate
    FROM level_users
    WHERE userId IN (?a)",
    $this->nominateUserIds);
    
    // Добавляет или заменяет уровень пользователя
    foreach ($this->nominateUserIds as $userId) {
      // Если прошло необходимое количество времени начиная с момента прошлого назначения
      if (isset($curLevels[$userId])) {
        //if ($curLevels[$userId]['nominateDate'] < time() - $this->interval) {
          db()->query('UPDATE level_users SET level=?d, nominateDate=? WHERE userId=?d',
            $this->level, dbCurTime(), $userId);
          $this->nominatedUserIds[] = $userId;
        //}
      } else {
        db()->query('INSERT INTO level_users SET level=?d, nominateDate=? WHERE userId=?d',
          $this->level, dbCurTime(), $userId);
        $this->nominatedUserIds[] = $userId;
      } 
    }
    */
        
    // Записывает в level-записи уровнь, для назначения которого эти записи были использованы
    if ($this->nominatedUserIds)
      db()->query('UPDATE level_items SET usedLevel=?d WHERE userId IN (?a)',
        $this->level, $this->nominatedUserIds);
  }
  
  /**
   * Возвращает level-записи для текущего уровня. Т.е. те записи, которые были использованы
   * для назначения уровня меньшего $this->level
   *
   * @return array
   */
  protected function getItems() {
    return db()->query("
    SELECT
      level_items.userId,
      level_items.type,
      COUNT(*) AS cnt,
      level_items.usedLevel 
    FROM level_items
    LEFT JOIN level_users ON level_users.userId=level_items.userId AND
                             level_users.nominateDate < ?    
    WHERE
      level_items.usedLevel < ?d AND
      level_items.dateCreate < ? AND
      level_items.dateCreate > ?
    GROUP BY
      level_items.userId,
      level_items.type 
    ",
    date('Y-m-d H:i:s', $this->now - $this->interval),
    $this->level,
    date('Y-m-d H:i:s', $this->now),
    date('Y-m-d H:i:s', $this->now - $this->interval)
    );
  }
  
  /**
   * Добавляет в массив $this->nominateUserIds ID пользователей, нуждающихся
   * в назначении им текущего уровня. Для этого проверяется имеетсля ли среди
   * набранных level-записей достаточное их количество хотя бы одного типа указанного
   * в $this->requirements
   */
  protected function addNominateUsersOr() {
    foreach ($this->getItems() as $v) {
      if (isset($this->requirements[$v['type']]) and $v['cnt'] >= $this->requirements[$v['type']]) {
        $this->addNominateUser($v['userId']);
      }
    }
  }
  
  /**
   * Добавляет в массив $this->nominateUserIds ID пользователей, нуждающихся
   * в назначении им текущего уровня. Для этого проверяется имеетсля ли среди
   * набранных level-записей достаточное их количество всех указанных 
   * в $this->requirements типов
   */
  protected function addNominateUsersAnd() {
    $items = array();
    foreach ($this->getItems() as $v)
      $items[$v['userId']][$v['type']] = $v['cnt'];
    foreach ($items as $userId => $typeCounts) {
      foreach ($this->requirements as $type => $reqCnt) {
        if (!isset($typeCounts[$type]) or $typeCounts[$type] < $reqCnt)
          continue 2;
      }
      $this->addNominateUser($userId);
    }
  }
  
  protected function addNominateUser($userId) {
    if (!in_array($userId, $this->nominateUserIds))
      $this->nominateUserIds[] = $userId;
  }
  
  public function getNominatedUsers() {
    return $this->nominatedUserIds;
  }

}