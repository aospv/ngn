<?

define('ERROR_FRIENDS_ALREADY_IN_FRIENDS', 1);
define('ERROR_FRIENDS_NO_USER_ID', 2);
define('ERROR_FRIENDS_NO_FRIEND_ID', 3);
define('ERROR_FRIENDS_NO_SUCH_FRIEND', 4);
define('ERROR_FRIENDS_CANT_ADD_YOURSELF', 5);

class FriendsCommon {
  
  var $errors;
  
  var $errorsText;
  
  var $friendsTable = 'friends';
  
  var $usersTable = 'users';
  
  function __construct() {
    $this->errorsText = array(
      ERROR_FRIENDS_ALREADY_IN_FRIENDS => 'Пользователь уже у вас в друзьях',
      ERROR_FRIENDS_NO_USER_ID => 'Не введён ID пользователя',
      ERROR_FRIENDS_NO_FRIEND_ID => 'Не введён ID друга',
      ERROR_FRIENDS_NO_SUCH_FRIEND => 'Этот пользователь не является вашим другом',
      ERROR_FRIENDS_CANT_ADD_YOURSELF => 'Вы не можете добавлять в друзья самого себя'
    );
  }
  
  function addFriend($userId, $friendId, $accepted = 0) {
    if (!$userId = (int)$userId) {
      $this->errors[] = $this->errorsText[ERROR_FRIENDS_NO_USER_ID];
      return false;
    }
    if (!$friendId = (int)$friendId) {
      $this->errors[] = $this->errorsText[ERROR_FRIENDS_NO_FRIEND_ID];
      return false;
    }
    if ($userId == $friendId) {
      $this->errors[] = $this->errorsText[ERROR_FRIENDS_CANT_ADD_YOURSELF];
      return false;
    }
    if ($this->friendExists($userId, $friendId)) {
      $this->errors[] = $this->errorsText[ERROR_FRIENDS_ALREADY_IN_FRIENDS];
      return false;
    }

    $query = "INSERT INTO {$this->friendsTable} (userId, friendId, accepted)
              VALUES ($userId, $friendId, '$accepted')";
    db()->query($query);
      
    if (!$accepted) {
      $privMsgs = new PrivMsgs($userId);
      $privMsgs->parseHtml = false;
      $privMsgs->sendMsg($userId, $friendId, Tt::getTpl('privMsgs/friendAddMsg', array(
        'userData' => get_user_info($userId)
      )));      
    }
    return true;
  }
  
  /**
   * Enter description here...
   *
   * @param integer ID пользователя, друга, которого необходимо принять
   * @param integer ID друга, которого необходимо принять
   */
  function acceptFriend($userId, $friendId) {
    if (!$userId = (int)$userId) return;
    if (!$friendId = (int)$friendId) return;    
    if ($userId == $friendId) return;
    db()->query("UPDATE {$this->friendsTable} SET accepted=1 WHERE friendId=$userId AND userId=$friendId");
        
    $privMsgs = new PrivMsgs($userId);
    $privMsgs->parseHtml = false;
    $privMsgs->sendMsg($userId, $friendId, Tt::getTpl('privMsgs/friendAcceptMsg', array(
      'userData' => get_user_info($userId)
    )));
    
    // Добавляем этого пользователя в друзья к себе
    $this->addFriend($userId, $friendId, 1);
  }
  
  function declineFriend($userId, $friendId) {
    if (!$userId = (int)$userId) return;
    if (!$friendId = (int)$friendId) return;
    db()->query("DELETE FROM {$this->friendsTable} WHERE accepted=0 AND friendId=$userId AND userId=$friendId");

    $t = Tt::getTpl('privMsgs/friendDeclineMsg', array(
      'userData' => get_user_info($userId)
    ));
    
    logText($t);

    $privMsgs = new PrivMsgs($userId);
    $privMsgs->parseHtml = false;
    $privMsgs->sendMsg($userId, $friendId, Tt::getTpl('privMsgs/friendDeclineMsg', array(
      'userData' => get_user_info($userId)
    )));
  }
  
  function getQueue($userId) {
    global $_CONFIG_SITE;
    $r = db()->query("SELECT
                      id AS userId,
                      users.login,
                      users_pages.userId AS online
                    FROM
                      {$this->friendsTable} AS friends,
                      {$this->usersTable} AS users
                    LEFT JOIN users_pages ON users.id=users_pages.userId
                    WHERE
                      friends.accepted=0 AND
                      friends.friendId=$userId AND
                      friends.userId=users.id");
    if (!mysql_num_rows($r)) return false;
    while ($row = mysql_fetch_assoc($r)) {
      if ($row['image_sm']) $row['image_sm'] = $_CONFIG_SITE['user_photo_dir'].'/'.$row['image_sm'];
      $rows[] = $row;
    }
    return $rows;
  }
  
  function &getFriends($userId) {
    if (!$userId = (int)$userId) {
      $this->errors[] = $this->errorsText[ERROR_FRIENDS_NO_USER_ID];
      return false;
    }
    require_once(COREROOT.'includes/users/Users.class.php');
    $r = db()->query("SELECT users.*
                    FROM
                      {$this->friendsTable} AS friends,
                      {$this->usersTable} AS users
                    WHERE
                      friends.accepted=1 AND
                      friends.userId=$userId AND
                      friends.friendId=users.id");
    while ($row = mysql_fetch_assoc($r)) {
      Users::extendUserData($row, true, true);
      $rows[] = $row;
    }
    return $rows;
  }
  
  function friendExists($userId, $friendId) {
    if (!$userId = (int)$userId) return false;
    if (!$friendId = (int)$friendId) return false;    
    $r = db()->query("SELECT * FROM {$this->friendsTable}
                    WHERE userId=$userId AND friendId=$friendId");
    if (mysql_num_rows($r)) return true;
    else return false;
  }
  
  function removeFriend($userId, $friendId) {
    if (!$userId = (int)$userId) {
      $this->errors[] = $this->errorsText[ERROR_FRIENDS_NO_USER_ID];
      return false;
    }
    if (!$friendId = (int)$friendId) {
      $this->errors[] = $this->errorsText[ERROR_FRIENDS_NO_FRIEND_ID];
      return false;
    }
    if (1 or $this->friendExists($userId, $friendId)) {
      
      db()->query("DELETE FROM {$this->friendsTable}
                 WHERE userId=$userId AND friendId=$friendId");
      db()->query("DELETE FROM {$this->friendsTable}
                 WHERE userId=$friendId AND friendId=$userId");
      /*
      p("DELETE FROM {$this->friendsTable}
                 WHERE userId=$userId AND friendId=$friendId");
      p("DELETE FROM {$this->friendsTable}
                 WHERE userId=$friendId AND friendId=$userId");
      */
    } else {
      $this->errors[] = $this->errorsText[ERROR_FRIENDS_NO_SUCH_FRIEND];
    }
  }
  
  function jointFriends($userId1, $userId2) {
    $query = "SELECT userId, friendId FROM {$this->friendsTable}
              WHERE userId=$userId1 OR userId=$userId2
              ORDER BY userId";
    $r = db()->query($query);
    while (list($userId, $friendId) = mysql_fetch_assoc($r)) {
      if (in_array($friendId, $friends)) {
        $jointFriends[] = $friendId;
      }
      $friends[] = $friendId;
    }
    if ($jointFriends) {
      $query = "SELECT * FROM users WHERE
                  id IN (".implode(', ', $jointFriends).") AND
                  active=1";
      $r = db()->query($query);
      while ($row = mysql_fetch_assoc($r)) {
      $rows[] = $row;
    }
    return $rows;
  } else {
      return false;
    }
  }
  
}

?>