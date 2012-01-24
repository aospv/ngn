<?

class ContactList {
  
  function getContacts($ownId) {
    $query = "SELECT
                contactUserId AS userId,
                friends.userId AS isFriend,
                friends.accepted,
                users.login,
                users_pages.userId AS online
              FROM contactlist
              LEFT JOIN users ON users.id=contactlist.contactUserId
              LEFT JOIN users_pages ON users.id=users_pages.userId
              LEFT JOIN friends ON
                friends.friendId=contactlist.contactUserId AND
                friends.userId=$ownId
              WHERE contactlist.userId=$ownId
              ORDER BY
                contactlist.lasttime DESC,
                friends.accepted DESC";
    
//    print_pre($query);
    
    $r = db()->query($query);
    while ($row = mysql_fetch_assoc($r)) {
      if ($row['isFriend']) {
        if ($row['accepted']) $row['friend'] = 1;
        else $row['waitFriend'] = 1;
      }      
      $rows[] = $row;
    }    
    return @$rows;
  }

  function addContact($ownId, $contactUserId) {
    if ($ownId == $contactUserId) return false;
    $r = db()->query("SELECT * FROM contactlist WHERE userId=$ownId AND contactUserId=$contactUserId");
    if (mysql_num_rows($r)) return;
    db()->query("INSERT INTO contactlist (userId,  contactUserId) VALUES ($ownId, $contactUserId)");
  }
  
  function deleteContact($ownId, $contactUserId) {
    // Удаляем из контакт-листа
    $r = db()->query("DELETE FROM contactlist WHERE userId=$ownId AND contactUserId=$contactUserId");
    // Удаляем из друзей
    $friends = new FriendsContactList();
    $friends->removeFriend($ownId, $contactUserId);
    // Удаляем историю разговора с этим пользователем
    $privMsgs = new PrivMsgs($ownId);
    $privMsgs->deleteChat($ownId, $contactUserId);
  }
  
}
