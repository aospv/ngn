<?php

class RatingVoterFactory {
  
  static public function getVoter(VoteObject $voteObject) {
    $conf = Config::getVar('dd');
    if ($conf['ratingVoterClass'] == 'RatingVoterSimple') {
      return O::get($conf['ratingVoterClass'], $voteObject);
    } else {
      if (!($userId = Auth::get('id')))
        throw new NgnException('User must be authorized');
      return O::get($conf['ratingVoterClass'], $voteObject, $userId);
    }
  }
  
}