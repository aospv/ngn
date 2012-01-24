<?php

class RatingVoterAuthDd extends RatingVoterAbstract {
  
  /**
   * @var VoteObjectDd
   */
  protected $voteObject;  

  /**
   * ID голосующего пользователя
   *
   * @var integer
   */
  protected $voterId;
  
  public function __construct(VoteObjectDd $voteObject, $voterId) {
    parent::__construct($voteObject);
    $this->voterId = $voterId;
    $this->init();
  }
  
  protected function init() {
  }
  
  protected function voted($id) {
    return (bool)db()->query('
    SELECT itemId FROM rating_dd_voted_users
    WHERE strName=? AND itemId=?d AND userId=?d',
    $this->voteObject->strName, $id, $this->voterId);
  }
  
  protected function logVoter($id, $votes) {
    db()->query('INSERT INTO rating_dd_voted_users SET ?a', array(
      'strName' => $this->voteObject->strName,
      'itemId' => $id,
      'userId' => $this->voterId,
      'voteDate' => dbCurTime(),
      'votes' => $votes
    ));
    $this->sendNoticeToOwner($id, $votes);
  }
  
  protected function sendNoticeToOwner($itemId, $votes) {
    $item = db()->selectRow('
    SELECT
      dd.*,
      pages.path AS pagePath
    FROM dd_i_'.$this->voteObject->strName.' AS dd
    LEFT JOIN pages ON dd.pageId=pages.id
    WHERE dd.id=?d',
    $itemId);
    if (!$item['userId']) return;
    $oSender = new Notify_SenderRobot();
    $oSender->sendIfSubscribed(
      'vote_ownItems',
      $item['userId'],
      "За вашу запись на сайте ".SITE_TITLE." проголосовали",
      Tt::getTpl('notify/msgs/vote_ownItems', array(
        'voter' => DbModelCore::get('users', $this->voterId),
        'votes' => $votes,
        'item' => $item
      ))
    );    
  }
  
  public function getVotedIds(array $inIds) {
    return db()->selectCol('
    SELECT itemId FROM rating_dd_voted_users
    WHERE itemId IN (?a) AND strName=? AND userId=?d',
    $inIds, $this->voteObject->strName, $this->voterId);
  }
  
  protected function saveAverage($id) {
    $r = db()->selectRow('
    SELECT SUM(votes) AS s, COUNT(votes) AS c FROM rating_dd_voted_users
    WHERE itemId=?d', $id);
    db()->query(
      'UPDATE dd_i_'.$this->voteObject->strName.' SET rating_average=?d WHERE id=?d',
      round($r['s']/$r['c']), $id);
  }
  
}
