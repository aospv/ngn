<?php

class Voting {

  private function isVoted($pageId, $itemId, $fieldName, $userId) {
    return db()->query("
      SELECT * FROM voting_log
      WHERE pageId=?d AND itemId=?d AND fieldName=? AND userId=?d",
      $pageId, $itemId, $fieldName, $userId
    );
  }
  
  public function canVote($userId) {
    return true;
  }
  
  public function vote($pageId, $itemId, $fieldName, $userId, DdItems $oItems) {
    if (self::isVoted($pageId, $itemId, $fieldName, $userId)) return;
    db()->query("
      UPDATE {$oItems->table} SET $fieldName=$fieldName+1 WHERE pageId=?d AND id=?d",
      $pageId, $itemId);
    db()->query(
      "INSERT INTO voting_log SET pageId=?d, itemId=?d, fieldName=?, userId=?d",
      $pageId, $itemId, $fieldName, $userId);
  }
  
}
