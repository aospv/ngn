<?php

/**
 * Класс осуществляет управления нотификационными подписками
 * 
 * @todo Переименовать класс... название не соответствует действительности
 *
 */
class Notify_SubscribeTypes {

  /**
   * Типы подписок
   * 
   * @var   array
   */
  static private $types = array(
    'comments_newMsgs' => array(
      'title' => 'новые комментарии в подписанных записях',
    ),
    'comments_ownItems' => array(
      'title' => 'новые комментарии в своих записях',
    ),
    'comments_answers' => array(
      'title' => 'ответы на свои комментарии',
    ),
    'items_new' => array(
      'title' => 'новые записи в подписанных разделах',
    ),
    'items_ownChange' => array(
      'title' => 'изменения собственных записей',
    ),
    'vote_ownItems' => array(
      'title' => 'голосования за свои записи',
    ),
    /*
    'level_nominate' => array(
      'title' => 'назначение уровней',
    ),
    */
    // Модераторские уведомления
    'event_createItem' => array(
      'title' => 'событие создания записи',
      'moder' => true
    ),
    'event_updateItem' => array(
      'title' => 'событие изменения записи',
      'moder' => true
    ),
    'event_deleteItem' => array(
      'title' => 'событие удаления записи',
      'moder' => true
    ),
    'event_activateItem' => array(
      'title' => 'событие активации записи',
      'moder' => true
    ),
    'event_deactivateItem' => array(
      'title' => 'событие дезактивации записи',
      'moder' => true
    ),
    'event_moveItem' => array(
      'title' => 'событие перемещения записи',
      'moder' => true
    ),
  );
  
  /**
   * Устанавливает подписку для заданного пользователя на сообщения определённого типа
   *
   * @param   integer   ID пользователя
   * @param   array     Массив с типами подписок
   */
  static function update($userId, $types) {
    db()->query(
      "DELETE FROM notify_subscribe_types WHERE userId=?d", $userId);
    foreach ($types as $type) self::add($userId, $type);
    // Для типов с записями проверяем, если не было добавлено такого типа, 
    // значит нужно удалить подписку на все записи
    foreach (array_keys(self::getTypes()) as $type) {
      if (!in_array($type, $types)) Notify_SubscribeItems::clear($userId, $type);
    }
  }
  
  static function add($userId, $type) {
    if (!strstr($type, '_'))
      throw new NgnException('"_" not exists in type "'.$type.'"');
    db()->query(
      "REPLACE INTO notify_subscribe_types SET 
       userId=?d, type=?, dateCreate=?, dateSent=?",
      $userId, $type, dbCurTime(), dbCurTime());
  }  
  
  static function touch($userId, $type, $minus = 0) {
    db()->query(
      "UPDATE notify_subscribe_types SET dateSent=? WHERE userId=?d AND type=?",
      date('Y-m-d H:i:s', time() - $minus), $userId, $type);
  }
  
  static function touchAll($userIds = null) {
    $cond = ($userIds and count($userIds)) ?
      "WHERE userId IN (".implode(', ', $userIds).")" : '';
    db()->query(
      "UPDATE notify_subscribe_types SET dateSent=? $cond",
      dbCurTime());
  }
  
  /**
   * Снимает подписку для заданного пользователя на сообщения определённого типа
   *
   * @param   integer   ID пользователя
   * @param   string    Тип сообщений
   */
  static function delete($userId, $type) {
    db()->query(
      "DELETE FROM notify_subscribe_types WHERE userId=?d AND type=?", $userId, $type);
  }
  
  /**
   * Возвращает все доступные типы подписок
   *
   * @return array
   */
   static public function getTypes() {
    $types = array();
    foreach (self::$types as $name => $type)
      if (empty($type['moder']))
        $types[$name] = $type['title'];
    return $types;
  }
  
  /**
   * Возвращает все существующие типы подписок
   *
   * @return array
   */
   static public function getModerTypes() {
    $types = array();
    foreach (self::$types as $name => $type)
      $types[$name] = $type['title'];
    return $types;
  }
  
  /**
   * Получает типы сообщений, на которые подписан пользователь
   *
   * @param   integer   ID пользователя
   * @return  array     Типы сообщений
   */
  static function getUserTypes($userId) {
    return db()->selectCol(
      "SELECT type FROM notify_subscribe_types WHERE userId=?d", $userId);
  }
  
  /**
   * Получает всех пользователей
   *
   * @return array
   */
  static function getUsers() {
    return db()->select(
      "SELECT * FROM notify_subscribe_types ORDER BY userId");
  }
  
  /**
   * Проверяет существует ли подписка для заданного пользователя на сообщения определенного типа
   *
   * @param   integer   ID пользователя
   * @param   string    Тип сообщений
   * @return  bool
   */
  static function isNotify($userId, $type) {
    return db()->selectCell(
      "SELECT userId FROM notify_subscribe_types WHERE userId=?d AND type=?",
      $userId, $type) ? true : false;
  }

}