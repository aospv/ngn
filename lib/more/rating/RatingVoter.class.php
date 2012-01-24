<?php

class RatingVoter {
  
	/**
	 * @param  VoteObjectDd $voteObject
	 * @return RatingVoterAbstract
	 */
  static public function getVoterDd(VoteObjectDd $voteObject) {
    $conf = Config::getVar('rating');    
    if (empty($conf['ratingVoterType']))
      throw new NgnException('ratingVoterType not defined in config');
    $voterClass = 'RatingVoter'.ucfirst($conf['ratingVoterType']).'Dd';
    if ($conf['ratingVoterType'] == 'simple') {
      return O::get($voterClass, $voteObject);
    } else {
      if (!($userId = Auth::get('id'))) return false;
      return O::get($voterClass, $voteObject, $userId);
    }
  }
  
}