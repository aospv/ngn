<?php

class CtrlCommonRating extends CtrlCommon {
  
  public function action_ajax_voters() {
    if (!Config::getVarVar('rating', 'allowVotingLogForAll'))
      throw new NgnException('Voting log not allowed');
    
    if (Config::getVarVar('rating', 'ratingVoterType') == 'simple') {
      print 'method not realized';
    } else {
      $voters = db()->query('
      SELECT
        vu.userId,
        u.login,
        vu.votes,
        UNIX_TIMESTAMP(vu.voteDate) AS voteDate_tStamp
      FROM rating_dd_voted_users AS vu
      LEFT JOIN users AS u ON vu.userId=u.id
      WHERE
        vu.strName=? AND
        vu.itemId=?d
      ', $this->params[3], $this->params[4]);
      foreach ($voters as &$voter) $voter['login'] = $voter['login'].' ('.$voter['votes'].')';
      Tt::tpl('common/users', $voters);
    }
    
  }
  
}
