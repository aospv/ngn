<?php

class Notify_Send {
  
  /**
   * Отправляет уведомления подписанным пользователям
   *
   * @param   array   ID пользователей, которыми нужно ограничиться при 
   *                  отправке уведомлений
   * @return  integer Количество пользователей, которым были отправлены уведомления
   */
  static function send($userIds = null) {    
    $n = 0;
    $oSText = new Notify_SubscribeText();
    $oSender = new Notify_SenderRobot();
    $recipientIds = array();
    foreach (Notify_SubscribeTypes::getUsers() as $user) {
      if ($userIds and !in_array($user['userId'], $userIds)) continue;
      if (in_array($user['userId'], $recipientIds))
        $recipientIds[] = $user['userId'];
      if (($text = $oSText->getText(
        $user['userId'],
        $user['type'],
        $user['dateSent']
      ))) {
        if ($oSender->send($user['userId'], 'Уведомление', $text)) {
          $n++;
        }
      }
    }
    Notify_SubscribeTypes::touchAll($recipientIds);
    return $n;
  }
  
}