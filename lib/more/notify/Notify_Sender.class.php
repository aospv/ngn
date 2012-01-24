<?php

class Notify_Sender {
  
  /**
   * Посылать повторно одному и тому же пользователю
   *
   * @var   bool
   */
  public $sendToUserAgain = false;
  
  /**
   * ID пользователей, которым были разосланы сообщения
   *
   * @var   array
   */
  public $sentToUserIds;
  
  /**
   * Отправляет сообщение от одного пользователя другому, указаными способами
   *
   * @param   integer   ID отправителя
   * @param   integer   ID получателя
   * @param   array     Массив со способами отправки
   * @param   string    Заголовок сообщения
   * @param   string    Текст сообщения
   * @return  bool
   */
  public function send($fromUserId, $toUserId, $sendMethods, $title, $text) {
    $n = 0;
    // Если у этого пользователя есть такой метод подписки
    $userSendMethods = UsersSettings::get($toUserId, 'sendMethods');
    foreach ($sendMethods as $sendMethod) {
      $method = 'send_'.$sendMethod;
      if (!method_exists($this, $method)) continue;
      // Если нельзя отправлять одному и тому же юзеру больше 1 раза, и этому 
      // пользователю уже отправлено сообщение
      if (!$this->sendToUserAgain and 
           isset($this->sentToUserIds[$method]) and
           is_array($this->sentToUserIds[$method]) and
           in_array($toUserId, $this->sentToUserIds[$method])) continue;
      // Если определены методы отправки для этого пользователи и среди них нет текущего
      // метода, то ничего не отправляем
      if ($userSendMethods and !in_array($sendMethod, $userSendMethods)) {
        continue;
      }
      // ------
      if ($this->$method($fromUserId, $toUserId, $title, $text)) {
        $this->sentToUserIds[$method][] = $toUserId;
        $n++;
      }
    }
    if ($n) return true; // Если сработал хотя бы один метод отправки
    return false;
  }
  
  public function sendIfSubscribed($subscribeType, $fromUserId, $toUserId, $sendMethods, $title, $text) {
    if (!in_array($subscribeType, Notify_SubscribeTypes::getUserTypes($toUserId)))
      return;
    return $this->send($fromUserId, $toUserId, $sendMethods, $title, $text);
  }
  
  public function send_privMsgs($fromUserId, $toUserId, $title, $text) {
    $privMsgs = new PrivMsgs($fromUserId);
    $r = $privMsgs->sendMsg($fromUserId, $toUserId,
      '<p><b>'.$title.'</b></p>'.$text, false);
    return $r ? true : false;
  }
  
  public function send_email($fromUserId, $toUserId, $title, $text) {
    if (($toUserData = DbModelCore::get('users', $toUserId)) === false) {
      Err::warning("User ID=$toUserId not found");
      return;
    }
    if (!$toUserData['email']) {
      Err::warning("User email ID=$toUserId not defined");
      return;
    }
    $sendEmail = new SendEmail();
    return $sendEmail->send($toUserData['email'], $title, $text);
  }
  
  public function getSendMethods() {
    $methods = array();
    foreach (get_class_methods($this) as $method) {
      if (!preg_match('/^send_(.*)$/', $method, $m)) continue;
      $methods[] = $m[1];
    }
    return $methods;
  }
  
}
