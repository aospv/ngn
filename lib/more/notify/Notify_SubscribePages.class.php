<?php

/**
 * Класс отвечает за кправление подписками на события разделов
 *
 */
class Notify_SubscribePages {
  
  static function update($userId, $event, $pageId) {
    Notify_SubscribeTypes::add($userId, $event);
    db()->query(
      "REPLACE INTO notify_subscribe_pages SET userId=?d, event=?, pageId=?d",
      $userId, $event, $pageId);
  }
  
  static function delete($userId, $event, $pageId) {
    db()->query(
      "DELETE FROM notify_subscribe_pages WHERE
       userId=?d AND event=? AND pageId=?d",
      $userId, $event, $pageId);
  }
  
  static function clear($userId, $event) {
    db()->query(
      "DELETE FROM notify_subscribe_pages WHERE userId=?d AND event=?",
      $userId, $event);
  }
  
  static function subscribed($userId, $event, $pageId) {
    return db()->select(
      "SELECT * FROM notify_subscribe_pages WHERE
       userId=?d AND event=? AND pageId=?d",
      $userId, $event, $pageId) ? true : false;
  }
  
  static function getSubscribedItems($userId, $event) {
    return db()->select("
    SELECT
      esp.pageId,
      p.title AS pageTitle,
      p.path AS pagePath
    FROM notify_subscribe_pages AS esp
    LEFT JOIN pages AS p ON esp.pageId=p.id 
    WHERE esp.userId=?d AND esp.event=?
    ORDER BY p.oid",
      $userId, $event);
  }
  
  }