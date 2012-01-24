<?php

class RatingVoterLevel extends RatingVoterAuth {
  
  protected function init() {
    $userLevel = (int)db()->selectCell(
      'SELECT level FROM level_users WHERE userId=?d', $this->voterId);
    if (!$userLevel) throw new NgnException('User level is null');
  }
  
}
