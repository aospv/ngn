<?php

/**
 * Класс для управления подписками на конкретные записи
 *
 */
class Notify_SubscribeItems {
  
  /**
   * Подписывает пользователя на уведомления для указанной записи
   *
   * @param   integer   ID пользователя
   * @param   string    см. notify/Notify_SubscribeTypes
   * @param   integer   ID записи
   */
  static function update($userId, $type, $pageId, $itemId) {
    Notify_SubscribeTypes::add($userId, $type);
    db()->query(
      "REPLACE INTO notify_subscribe_items SET userId=?d, type=?, pageId=?d, itemId=?d",
      $userId, $type, $pageId, $itemId);
    Notify_SubscribeTypes::add($userId, $type);
  }
  
  static function delete($userId, $type, $pageId, $itemId) {
    db()->query(
      "DELETE FROM notify_subscribe_items WHERE
       userId=?d AND type=? AND pageId=?d AND itemId=?d",
      $userId, $type, $pageId, $itemId);
  }
  
  static function clear($userId, $type) {
    db()->query(
      "DELETE FROM notify_subscribe_items WHERE userId=?d AND type=?",
      $userId, $type);
  }
  
  static function subscribed($userId, $type, $pageId, $itemId) {
    return db()->select(
      "SELECT * FROM notify_subscribe_items WHERE
       userId=?d AND type=? AND pageId=?d AND itemId=?d",
      $userId, $type, $pageId, $itemId) ? true : false;
  }
  
  static function getSubscribedItems($userId, $type) {
    return db()->select("
    SELECT
      nsi.itemId,
      nsi.pageId,
      p.title AS pageTitle,
      p.path AS pagePath,
      it.title AS itemTitle
    FROM notify_subscribe_items AS nsi
    LEFT JOIN pages AS p ON p.id=nsi.pageId
    LEFT JOIN dd_items AS it ON
      nsi.pageId=it.pageId AND
      nsi.itemId=it.itemId
    WHERE nsi.userId=?d AND nsi.type=?",
      $userId, $type);
  }
  
}
