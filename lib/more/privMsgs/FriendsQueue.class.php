<?

class FriendsQueue {
  
  function addFriend($userId, $friendId) {
    $r = db()->query("SELECT * FROM friends_queue WHERE userId=$userId AND friendId=$friendId");
    if (!mysql_num_rows($r)) db()->query("INSERT INTO friends_queue (userId, friendId) VALUES ($userId, $friendId)");
  }
  
  function getQueue($userId) {
    $r = db()->query("SELECT * FROM friends_queue WHERE userId=$userId");
    while ($row = mysql_fetch_assoc($r)) $rows[] = $row;
    return $row;
  }

}

?>