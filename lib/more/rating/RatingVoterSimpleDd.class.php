<?php

class RatingVoterSimpleDd extends RatingVoterAbstract {
  
  /**
   * @var VoteObjectDd
   */
  protected $voteObject;  

  /**
   * IP голосующего пользователя
   *
   * @var string
   */
  protected $voterIp;
  
  public function __construct(VoteObjectDd $voteObject) {
    parent::__construct($voteObject);
    if (empty($_SERVER['REMOTE_ADDR']))
      throw new NgnException("\$_SERVER['REMOTE_ADDR'] is empty");
    $this->voterIp = $_SERVER['REMOTE_ADDR'];
  }
  
  protected function voted($id) {
    return (bool)db()->query('
    SELECT itemId FROM rating_dd_voted_ips
    WHERE strName=? AND itemId=?d AND ip=?',
    $this->voteObject->strName, $id, $this->voterIp);
  }  
  
  protected function logVoter($id, $n) {
    db()->query('INSERT INTO rating_dd_voted_ips SET ?a', array(
      'strName' => $this->voteObject->strName,
      'itemId' => $id,
      'ip' => $this->voterIp,
      'voteDate' => dbCurTime(),
      'votes' => $n
    ));
  }
  
  public function getVotedIds(array $inIds) {
    return db()->selectCol('
    SELECT itemId FROM rating_dd_voted_ips
    WHERE itemId IN (?a) AND strName=? AND ip=?',
    $inIds, $this->voteObject->strName, $this->voterIp);
  }
    
}
