<?php

class CtrlContactList extends CtrlFriends {
  
  /**
   * Contacts Object
   *
   * @var ContactList
   */
  var $contactList;
  
  function setContactList() {
    $this->contactList = new ContactList();
  }
  
  function getContacts() {
    $this->setContactList();
    if ($users = $this->contactList->getContacts($this->userId)) {
      //foreach ($users as $k => $v) {
       //if ($v['image_sm']) $users[$k]['image_sm'] = $_CONFIG_SITE['user_photo_dir'].'/'.$users[$k]['image_sm'];
      //}
      return $users;
    }
    return false;
  }
  
  function action_ajaxAddFriend() {
    $this->hasOutput = false;
    if (!$contactUserId = (int)$this->oReq->r['contactUserId']) return;
    $this->setFriends();
    $this->friends->addFriend($this->userId, $contactUserId);
    $this->setContactList();
    $this->contactList->addContact($this->userId, $contactUserId);
  }
  
  function action_ajaxAddContact() {
    $this->hasOutput = false;
    if (!$contactUserId = (int)$this->oReq->r['contactUserId']) return;
    $this->setContactList();
    $this->contactList->addContact($this->userId, $contactUserId);
  }
  
  function action_ajaxGetContacts() {
    $this->hasOutput = false;
    Tt::tpl(
      'privMsgs/usersListContextMenu',
      array('users' => $this->getContacts()),
      $this->moduleName
    );
  }
  
  function action_ajaxDeleteContact() {
    $this->hasOutput = false;
    if (!$contactUserId = (int)$this->oReq->r['contactUserId']) return;
    $this->setContactList();
    $this->contactList->deleteContact($this->userId, $contactUserId);
  }
  
}
