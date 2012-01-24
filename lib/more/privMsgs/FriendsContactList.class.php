<?

class FriendsContactList extends FriendsCommon {
  
  function acceptFriend($userId, $friendId) {
    parent::acceptFriend($userId, $friendId);
    $contactList = new ContactList();
    $contactList->addContact($userId, $friendId);
    $contactList->addContact($friendId, $userId);
  }
  
}

?>