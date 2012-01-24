<?php

class CtrlSearchUser extends CtrlPage {
  
  function action_ajaxSearchUser() {
    global $_CONFIG_SITE;
    $this->hasOutput = false;
    if (!$userSearch = $_POST['userSearch']) return;
    if ($this->userId) {
      $userIdCond = 'AND id!='.$this->userId;
      $friendsCond1 = 'friends.userId AS isFriend,
                       friends.accepted,';
      $friendsCond2 = 'LEFT JOIN friends ON
                         friends.friendId=contactlist.contactUserId AND
                         friends.userId='.$this->userId;
    }    
    $query = "SELECT
                id AS userId,
                $friendsCond1
                users.login,
                users_pages.userId AS online,
                users.status_id,
                users.usercat_id,
                users.name,
                users.city,
                users.image_sm,
                DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(user_birthday)), '%Y')+0 AS age
              FROM users
              LEFT JOIN contactlist ON users.id=contactlist.contactUserId
              LEFT JOIN users_pages ON users.id=users_pages.userId
              $friendsCond2
              WHERE login LIKE '$userSearch%' ".@$userIdCond." LIMIT 5";
    $r = db()->query($query);
    while ($row = mysql_fetch_assoc($r)) {
      if ($row['image_sm']) $row['image_sm'] = $_CONFIG_SITE['user_photo_dir'].'/'.$row['image_sm'];
      $rows[] = $row;
    }
    Tt::tpl('privMsgs/usersListContextMenu', array(
      'addContactBtns' => true,
      'users' => @$rows
    ), $this->moduleName);
  }
    
}
