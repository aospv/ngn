<?php

/**
 * Обеспечивает получение данных об обновлениях комментариев в
 * следующих случаях:
 * - Новые комментарии в подписанных юзером записях
 * - Новые комментарии в своих записях
 * - Ответы на свои комментарии
 */
class Notify_SubscribeText_comments {
  
  function getData_newMsgs($userId, $lastSendTime) {
    if (!($items = Notify_SubscribeItems::getSubscribedItems(
      $userId, 'comments_newMsgs'))) return false;
    $data = array();
    foreach ($items as $v) {
      $this->setNewComments($data, $v['pageId'], $v['itemId'], $userId, $lastSendTime);
    }
    $this->setExtraData($data);
    return $data;
  }
  
  function getData_ownItems($userId, $lastSendTime) {
    foreach (db()->ddTables() as $table) {
      foreach (db()->select(
      "SELECT id, pageId FROM {$table} WHERE userId=?d", $userId) as $v) {
        $cond[] = "\n (parentId={$v['pageId']} AND id2={$v['id']})";
      }
    }
    $cond = isset($cond) ? "\n(".implode(' OR ', $cond)."\n)" : '';
    $data = array();
    $this->setOwnItemsNewComments($data, $userId, $lastSendTime, $cond);    
    $this->setExtraData($data);
    return $data;
  }
  
  function getData_answers($userId, $lastSendTime) {
    $data = array();
    $this->setNewAnswers($data, $userId, $lastSendTime);
    $this->setExtraData($data);
    return $data;
  }  
  
  function _setNewComments(&$data, $lastSendTime, $extraCond = '') {
    foreach (db()->select("
      SELECT
        comments.id,
        comments.parentId AS pageId,
        comments.id2 AS itemId,
        comments.text_f AS text,
        comments.userId,
        comments.nick,
        users.login,
        comments.ansUserId,
        users2.login AS ansLogin
      FROM comments
      LEFT JOIN users ON comments.userId=users.id
      LEFT JOIN users AS users2 ON comments.ansUserId=users2.id
      WHERE
        comments.dateCreate > ?
        $extraCond
      ORDER BY comments.dateCreate DESC",
      $lastSendTime) as $v) {
      $data[$v['pageId']]['items'][$v['itemId']]['items'][] = $v;
    }
    return $data;
  }

  /**
   * Добавляет данные о разделах и записях в массив
   *
   * @param array
   */
  private function setExtraData(&$data) {
    foreach ($data as $pageId => &$page) {
      $page['data'] = O::get('Pages')->getNode($pageId);
      if (!$page['data']['strName']) continue;
      $oI = O::get('DdItems', $page['data']['id']);
      foreach ($page['items'] as $itemId => &$item) {
        $item['data'] = $oI->getItem($itemId);
      }
    }    
  }
  
  private function setNewComments(&$data, $pageId, $itemId, $userId, $lastSendTime) {
    $this->_setNewComments($data, $lastSendTime, 
                           "AND comments.userId != $userId ".
                           "AND comments.parentId = $pageId ".
                           "AND comments.id2 = $itemId");    
  }
  
  private function setOwnItemsNewComments(&$data, $userId, $lastSendTime, 
                                          $cond = '') {
    $this->_setNewComments($data, $lastSendTime, 
                           "AND comments.userId != $userId ".
                           ($cond ? "AND $cond" : ''));
  }
  
  private function setNewAnswers(&$data, $userId, $lastSendTime) {
    $this->_setNewComments($data, $lastSendTime, 
                           "AND comments.ansUserId = $userId");
  }  

}
