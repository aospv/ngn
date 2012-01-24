<?php

class PrivMsgs {
  
  /**
   * ID пользователя
   *
   * @var integer
   */
  public $userId;
  
  /**
   * Логин/имя пользователя
   *
   * @var string
   */
  public $userLogin;

  /**
   * Форматировать ли текст, как HTML
   *
   * @var bool
   */
  public $parseHtml = false;
  
  /**
   * Основная таблица сообщений
   *
   * @var string
   */
  static $table = 'priv_msgs';
  
  /**
   * Конструктор
   *
   * @param integer ID текущего пользователя
   * @param string  Логин/имя текущего пользователя
   */
  function __construct($userId = null) {
    $this->userId = @(int)$userId;
  }

  /**
   * Получает массив пользователей, от которых пришли новые сообщения
   * (для текущего пользователя)
   *
   * @return mixed  Массив пользователей
   */
  function getNewMsgUserIds() {
    if (!$this->userId) return false;

    $newMessages = array();

    $query = "SELECT fromUserId FROM ".self::$table."
              WHERE 
              userId={$this->userId} AND 
              toUserId={$this->userId} AND 
              viewed='0'";

    $r = db()->query($query);

    while (list($us_id) = mysql_fetch_row($r)) {
      if (!in_array($us_id, $newMessages)) $newMessages[] = $us_id;
    }

    return count($newMessages) ? $newMessages : false;
  }

  function getMsgsCounts() {
    if (!$userId = $this->userId) return false;

    $query = "SELECT fromUserId FROM ".self::$table."
              WHERE userId=$userId AND toUserId=$userId";

    $r = db()->query($query);

    $newMsgsUsers = array();
    while (list($curUserId) = mysql_fetch_row($r)) {
      if (!in_array($curUserId, $newMsgsUsers)) $newMsgsUsers[] = $curUserId;
    }

    return $newMessages;
  }
  
  function getNewMsgs() {
    $q = "
    SELECT
      pmt.fromUserId AS userId,
      users.login,
      pmt.id,
      pmt.pairId,
      pmt.text, 
      pmt.status,
      pmt.time1,
      pmt.viewed
    FROM priv_msgs AS pmt
    LEFT JOIN users ON pmt.fromUserId=users.id
    WHERE
      pmt.toUserId={$this->userId} AND
      pmt.userId={$this->userId} AND 
      viewed=0
    ORDER BY time1 DESC";    
    foreach (db()->query($q) as $k => $v) {
      $v += UsersCore::getImageData($v['userId']);
      $r[$k] = $v;
    }
    return $r;
  }

  /**
   * Возвращает массив пользователей с информацией о них,
   * от которых существуют новые сообщения для текущего юзера
   *
   * @return array массив пользователей
   */
  function getNewMsgsUsers() {
    if ($userIds = self::getNewMsgUserIds()) {
      if (count($userIds) > 0) {
        $query = "SELECT id, login FROM users
                  WHERE id IN (".implode(",", $userIds).")";
        $r = db()->query($query);
        while ($row = mysql_fetch_assoc($r)) {
          $users[] = $row;
        }
        //die2($users);
        if (count($users)) return $users;
      }      
    }
    return false;
  }

  function getMsgsUsers() {
    if ($userIds = getMsgsCounts()) {
      if (count($userIds) > 0) {
        $query = "SELECT id, login FROM ".PREFIX."_users
                  WHERE id IN (".implode(",", $userIds).") AND active=1";
        $r = db()->query($query);
        while ($row = mysql_fetch_assoc($r)) {
          $users[] = $row;
        }
        if (count($users)) return $users;
        else return false;
      }
      return false;
    }
    return false;
  }
  
  function getNewMsgsCount() {
    return db()->selectCell(
    "SELECT COUNT(*) FROM priv_msgs WHERE userId=?d AND viewed=0", $this->userId);
  }
  
  function deleteMsgs($ownUserId, $msgIds) {
    if (!$ownUserId = (int)$ownUserId) return;
    if (!is_array($msgIds) or !count($msgIds)) return;
    foreach ($msgIds as $k => $v) $msgIds[$k] = (int)$v;
    $query = "DELETE FROM ".self::$table."
              WHERE
              id IN (".implode(",", $msgIds).") AND
              userId=$ownUserId";
    db()->query($query);
  }
  
  function markViewed($ownUserId, $msgIds) {
    foreach ($msgIds as $id)
      db()->query("
        UPDATE ".self::$table." SET viewed=1
        WHERE userId=?d AND id IN (?d)", $ownUserId, $id);
  }
  
  function clearMsgs($ownUserId) {
    db()->query("DELETE FROM ".self::$table." WHERE userId=?d", $ownUserId);
  }
  
  function deleteChat($ownUserId, $toUserId) {
    $query = "DELETE FROM ".self::$table." WHERE
                userId=$ownUserId AND
                fromUserId=$ownUserId AND
                toUserId=$toUserId";
    db()->query($query);
    $query = "DELETE FROM ".self::$table." WHERE
                userId=$ownUserId AND
                toUserId=$ownUserId AND
                fromUserId=$toUserId";
    db()->query($query);
    $query = "DELETE FROM ".self::$table."_archive WHERE
                userId=$ownUserId AND
                fromUserId=$ownUserId AND
                toUserId=$toUserId";
    db()->query($query);
    $query = "DELETE FROM ".self::$table."_archive WHERE
                userId=$ownUserId AND
                toUserId=$ownUserId AND
                fromUserId=$toUserId";
    db()->query($query);
  }
  
  function deleteAuthMsgs($msgIds) {
    PrivMsgs::deleteMsgs($this->userId, $msgIds);
  }
  
  function formatHtml($text) {
    $o = new FormatText(array('allowedTagsConfigName' => 'privMsgs.allowedTags'));
    $o->oJevix->cfgSetAutoBrMode(true);
    return $o->html($text);
  }

  function sendMsg($fromUserId, $toUserId, $msgText,
                   $seveOutgoingHistory = true,
                   $makeViewedOutgoing = true) {
    if (!$toUserId = (int)$toUserId) {
    	warning('$toUserId not defined');
    	return false;
    }
    if (!$fromUserId = (int)$fromUserId) {
      Err::warning('$fromUserId not defined');
      return false;
    }
    if ($fromUserId == $toUserId) {
      Err::warning('$fromUserId and $toUserId can not be empty');
      return false;
    }
    

    $ipInfo = Misc::getIPInfo();
    
    $msgText = str_replace("'", "\\'", $msgText);
    
    if ($seveOutgoingHistory) {
      if ($makeViewedOutgoing) $viewed = 1;
      else $viewed = 0;
      
      $query = "INSERT INTO ".self::$table." () VALUES ()";
      db()->query($query);
      
      //prr($query);
      
      $msgId = mysql_insert_id();
      
      if ($this->parseHtml) {
        $msgTextF = $this->formatHtml($msgText, array(
          'fromUserId' => $fromUserId,
          'msgId' => $msgId
        ));
      } else {
        $msgTextF = $msgText;
      }      
      
      $query = "UPDATE ".self::$table." SET
                  userId=$fromUserId,
                  fromUserId=$fromUserId,
                  toUserId=$toUserId,
                  text='$msgTextF',
                  time1=".time().",
                  ip='".$ipInfo["ip"]."',
                  host='".$ipInfo["host"]."',
                  viewed=$viewed
                WHERE id=$msgId";
//      die2($query);
      db()->query($query);
      
      $query = "UPDATE ".self::$table."
                SET pairId=$msgId WHERE id=$msgId";
      db()->query($query);
    } else {
      $msgId = 0;
    }    

    $msgId2 = db()->query("INSERT INTO ".self::$table." () VALUES ()");
     
    
    

    if ($this->parseHtml) {
      $msgTextF2 = $this->formatHtml($msgText, array(
        'fromUserId' => $toUserId,
        'msgId' => $msgId2
      ));
    } else {
      $msgTextF2 = $msgText;
    }
    
    $query = "UPDATE ".self::$table." SET
                pairId=$msgId,
                userId=$toUserId,
                fromUserId=$fromUserId,
                toUserId=$toUserId,
                text='$msgTextF2',
                time1=".time().",
                ip='".$ipInfo["ip"]."',
                host='".$ipInfo["host"]."'
              WHERE id=$msgId2";

    db()->query($query);
    return array($msgId, $msgId2);
  }
  
  
  function setAuthMsgsViewed($msgCodes = array()) {
    return PrivMsgs::setMsgsViewed($this->userId, $msgCodes);
  }

  function setMsgsViewed($ownUserId, $msgCodes = array()) {
    if (!$ownUserId = (int)$ownUserId) return false;
    if (count($msgCodes) == 0) return false;
    $msgCodesCond = implode(", ", $msgCodes);
    $query = "UPDATE ".self::$table."
              SET viewed=1, time2=".time()."
              WHERE userId=$ownUserId AND id IN ($msgCodesCond)";
    db()->query($query);
    return true;
  }
  
  /**
   * Получаем сообщения для текущего авторизованного пользователя 
   * от другого пользователя
   *
   * @param integer $toUserId
   * @param bool    получить только непрочитанные сообщения
   * @param bool    получить сообщения без установки для них флага 'прочитано'
   * @param string  сортировка
   * @return array  массив с сообщениями
   */
  function getAuthMsgs($toUserId, $onlyNotViewed = true,
                       $notSetViwed = false, $order = "ASC") {
    return PrivMsgs::getMsgs($this->userId, $toUserId, $onlyNotViewed, $notSetViwed, $order);
  }
  
  function getMsgs($ownUserId, $toUserId, $onlyNotViewed = true,
                   $notSetViwed = false, $order = 'ASC', $archive = false) {
                     
    if (!$ownUserId = (int)$ownUserId) return false;
    if (!$toUserId = (int)$toUserId) return false;
    
    $table = $archive ? self::$table.'_archive' : self::$table;
    

    if ($onlyNotViewed) {
      $viewedCond = "AND viewed=0";
    }

    $query = "
    SELECT
      pmt.fromUserId AS userId,
      pmt.id,
      pmt.pairId,
      pmt.text, 
      pmt.status,
      pmt.time1, 
      pmt.viewed
    FROM $table AS pmt
    WHERE
      ((pmt.fromUserId=$ownUserId AND pmt.toUserId=$toUserId)
    OR (pmt.fromUserId=$toUserId AND pmt.toUserId=$ownUserId))
    AND pmt.userId=$ownUserId 
    ".$viewedCond."
    ORDER BY time1 ".$order;
    
    // Получаем сообщения и их прочитанность текущим юзером
    $r = db()->query($query);
    $msgCodesNotViewed = array();
    $msgPairCodes = array();
    $msgs = array();
    while (($row = mysql_fetch_assoc($r))) {
      $time1 = $row["time1"];
      $row["time1"] = date("H:i:s", $time1);
      $row["date"] = date("d.m.y", $time1);

      if ($time1 < time()-(60*60*24)) $row["IntellectualTime"] = date("d.m.Y H:i", $time1);
      else $row["IntellectualTime"] = date("H:i:s", $time1);

      $msgs[$row["pairId"]] = $row;
      if (!$row["viewed"]) $msgCodesNotViewed[] = $row["id"];
      $msgPairCodes[] = $row["pairId"];
    }

    if (count($msgPairCodes) != 0) {
      // Берём статусы просмотров для получателя, что бы узнать, что он просмотрел,
      // а что нет ещё (статус "доставлено")
      $msgCodesCond = implode(", ", $msgPairCodes);
      $query = "SELECT pairId, viewed FROM ".self::$table."
                WHERE pairId IN ($msgCodesCond) AND userId=$toUserId";
      $r = db()->query($query);
      while (list($msg_PairCode, $msg_Viewed) = mysql_fetch_row($r)) {
        $msgs[$msg_PairCode]['delivered'] = $msg_Viewed;
      }
    }
    if (!$notSetViwed) {
      if (count($msgCodesNotViewed) != 0) {
        PrivMsgs::setAuthMsgsViewed($msgCodesNotViewed);
      }
    }
    return $msgs;
  }
  
  function getAllMsgs() {
    $msgs = db()->select("
      SELECT
        pm.*,
        pm.id AS ARRAY_KEY,
        uf.login AS fromLogin
      FROM ".self::$table." AS pm
      LEFT JOIN users AS uf ON pm.fromUserId=uf.id 
      WHERE pm.userId=?d AND pm.toUserId=?d
      ORDER BY pm.time1 DESC", $this->userId, $this->userId);
    if (!$msgs) return false;
    $msgIds = array_keys($msgs);
    db()->query("UPDATE ".self::$table." SET viewed=1 WHERE id IN (".implode(', ', $msgIds).")");
    return $msgs;
  }
  
  function getOutMsgs() {
    return db()->select("
      SELECT
        pm.*,
        pm.id AS ARRAY_KEY,
        ut.login AS toLogin
      FROM ".self::$table." AS pm
      LEFT JOIN users AS ut ON pm.toUserId=ut.id
      WHERE pm.userId=?d AND pm.fromUserId=?d
      ORDER BY pm.time1 DESC", $this->userId, $this->userId);
  }
  
  function getHistory($ownUserId, $toUserId) {
    $msgs = $this->getMsgs($ownUserId, $toUserId, false, false, 'ASC', true);
    $msgs += @$this->getMsgs($ownUserId, $toUserId, false);
    return $msgs;
  }
  
}
