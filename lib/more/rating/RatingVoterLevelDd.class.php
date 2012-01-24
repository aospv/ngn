<?php

class RatingVoterLevelDd extends RatingVoterAuthDd {
  
  protected $userLevel;
  
  protected function init() {
    $this->userLevel = (int)db()->selectCell(
      'SELECT level FROM level_users WHERE userId=?d', $this->voterId);
  }
  
  protected function checkMaxN($n) {
    $level2stars = Arr::get(Config::getVar('levelStars'), 'level', 'maxStarsN');
    if (!isset($level2stars[$this->userLevel]))
      throw new NgnException("Stars not defined in config for level {$this->userLevel}");
    if ((int)$n > $level2stars[$this->userLevel])
      throw new NgnException("$n are to much stars for you");
    return true;
  }
  
}
