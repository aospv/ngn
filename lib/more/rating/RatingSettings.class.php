<?php

class RatingSettings {
  
  static public function getMaxStars() {
    $conf = Config::getVar('rating');
    if ($conf['ratingVoterType'] != 'level') {
      return (int)$conf['maxStarsN'];
    } else {
      if (!($userId = Auth::get('id')))
        return 0;
      $level2stars = Arr::get(Config::getVar('levelStars'), 'level', 'maxStarsN');
      $userLevel = (int)db()->selectCell(
        'SELECT level FROM level_users WHERE userId=?d', $userId);
      if (isset($level2stars[$userLevel]))
        return (int)$level2stars[$userLevel];
      else return 0;
    }
  }
  
}
