<?php

class SubsSender {
  
  protected $listId;
  
  /**
   * Пример массива:
   * array(
   *   array(
   *     'email' => 'email@email.em',
   *     'code' => 'f8uq2hf3qf',
   *     'type' => 'users',
   *   )
   * )
   *
   * @var array
   */
  protected $subscribers;
  
  /**
   * Использовать ли базу пользователей для осуществления рассылки
   *
   * @var bool
   */
  protected $useUsers;
  
  /**
   * Название рассылки
   *
   * @var string
   */
  protected $title;
  
  /**
   * Текст рассылки
   *
   * @var string
   */
  protected $text;
  
  /**
   * ID новой созданной рассылки
   *
   * @var integer
   */
  protected $subsId;
  
  /**
   * @var SendEmail
   */
  protected $oSender;
  
  public function __construct($listId, $subsId = null) {
    $this->listId = $listId;
    if (!($listData = db()->selectRow('SELECT * FROM subs_list WHERE id=?d', $this->listId)))
      throw new NgnException("Subscribe list ID=$listId does not exists");
    $this->useUsers = (bool)$listData['useUsers'];
    $this->title = $listData['title'];
    $this->text = $listData['text'];
    $this->oSender = new SendEmail();
    if (!$subsId) {
      // Создаем новую рассылку
      $this->createSubscribe();
    } else {
      // Используем имеющуюся
      $this->setSubsId($subsId);
    }
  }
  
  public function createSubscribe() {
    $this->subsId = db()->query('
    INSERT INTO subs_subscribes SET listId=?d, text=?, subsBeginDate=?',
      $this->listId, $this->text, dbCurTime());
    db()->multiInsert('subs_subscribers', $this->getSubscribers());
    return $this->subsId;
  }
  
  public function endSubscribe() {
    db()->query('UPDATE subs_subscribes SET subsEndDate=? WHERE id=?d',
      dbCurTime(), $this->subsId);
  }
  
  public function setSubsId($subsId) {
    if (!db()->query('SELECT * FROM subs_subscribes WHERE id=?d', $subsId))
      throw new NgnException("Subscribe with ID=$subsId does not exists");
    $this->subsId = $subsId;
    return $subsId;
  }
  
  public function getSubscribers() {
    $n = 0;
    $emails = array();
    // Ящики пользователей имеют более высокий приоритет над обычными ящиками.
    // Поэтому сначало добавляем их, а потом простые, если их ещё нет в списке.
    if ($this->useUsers) {
      foreach (db()->query("
      SELECT
        users.id,
        users.email,
        users.actCode AS code
      FROM subs_users, users
      WHERE
        subs_users.userId=users.id AND
        subs_users.listId=?d AND
        users.active=1
      ", $this->listId) as $v) {
        if (!$v['email']) continue;
        $n++;
        $v['n'] = $n;
        $v['type'] = 'users';
        $v['subsId'] = $this->subsId;
        $v['status'] = '';
        $subscribers[] = $v;
        $emails[] = $v['email'];
      }    
    }
    foreach (db()->query(
    'SELECT id, email, code FROM subs_emails WHERE listId=?d', $this->listId) as $v) {
      if (in_array($v['email'], $emails)) continue;
      if (!$v['email']) continue;
      $n++;
      $v['n'] = $n;
      $v['type'] = 'emails';
      $v['subsId'] = $this->subsId;
      $v['status'] = '';
      $subscribers[] = $v;  
    }
    return $subscribers;
  }
  
  public function getSubsId() {
    return $this->subsId;
  }
  
  public function getListId() {
    return $this->listId;
  }
  
  public function send() {
    if (!isset($this->subsId))
      throw new NgnException(
      '$this->subsId not defined. Use $this->createSubscribe or $this->setSubsId methods.');
    $this->initSubscribers();
    $this->oSender->send(
      Arr::get($this->subscribers, 'email'),
      SITE_TITLE.': '.$this->title,
      $this->text
    );
  }
  
  /**
   * Отправляет рассылку на email
   *
   * @param   array   Массив с данными о email'е
   *                  Пример:
   *                  array(
   *                    'type' => 'emails',
   *                    'code' => 'f87g23f9g732f',
   *                    'email' => 'masted311@gmail.com'  
   *                  )
   */
  public function sendEmail($subscriber) {
    if (!isset($this->subsId))
      throw new NgnException('$this->subsId not defined. Use $this->createSubscribe or $this->setSubsId methods.');
    $message = preg_replace(
      '/(href=["\'])\/*(.*)(["\'])/', '$1'.Tt::getPath(0).'/c/subs?subsId='.$this->subsId.
      '&type='.$subscriber['type'].'&code='.$subscriber['code'].'&link=$2$3',
      $this->text);
    $message .= '<hr /><a href="'.Tt::getPath(0).'/c/subs/unsubscribe?listId='.$this->listId.'&type='.
      $subscriber['type'].'&code='.$subscriber['code'].'">Отписаться</a>';
    $this->oSender->send($subscriber['email'], SITE_TITLE.': '.$this->title, $message);
  }
  
}