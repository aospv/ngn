<?php

class CtrlPageFriends extends CtrlSearchUser {
  
  /**
   * Friends object
   *
   * @var FriendsCommon
   */
  var $friends;
  
  function setFriends() {
    $this->friends = new FriendsContactList();
  }
  
  function action_ajaxGetFriendsQueue() {
    $this->hasOutput = false;
    $this->setFriends();
    Tt::tpl(
      'privMsgs/friendsQueue',
      array('friendsQueue' => $this->friends->getQueue($this->userId)),
      $this->moduleName
    );
  }
  
  function action_ajaxAddFriend() {
    $this->hasOutput = false;
    if (!$contactUserId = (int)$this->oReq->r['contactUserId']) return;
    $this->setFriends();
    $this->friends->addFriend($this->userId, $contactUserId);
  }

  function action_ajaxDeleteFriend() {
    $this->hasOutput = false;
    if (!$contactUserId = (int)$this->oReq->r['contactUserId']) return;
    $this->setFriends();
    $this->friends->removeFriend($this->userId, $contactUserId);
  }
  
  function action_ajaxAcceptFriend() {
    $this->hasOutput = false;
    if (!$contactUserId = (int)$this->oReq->r['contactUserId']) return;
    $this->setFriends();
    $this->friends->acceptFriend($this->userId, $contactUserId);
  }
  
  function action_ajaxDeclineFriend() {
    $this->hasOutput = false;
    if (!$contactUserId = (int)$this->oReq->r['contactUserId']) return;
    $this->setFriends();
    $this->friends->declineFriend($this->userId, $contactUserId);
  }
  
}
