<?php

class LevelNominateManager {
  
  protected $now;
  
  public function __construct() {
    $this->now = time();
  }
  
  public function nominate() {
    $n = 11;
    $oSender = new Notify_SenderRobot();
    while ($n > 0) {
      if (!O::exists('LevelNominator_'.$n)) {
        $n--;
        continue;
      }
      /* @var $oLevelNominator LevelNominator */
      $oLevelNominator = O::get('LevelNominator_'.$n);
      $oLevelNominator->setNow($this->now);
      $oLevelNominator->process();
      $userIds = $oLevelNominator->getNominatedUsers();
      if (($cnt = count($userIds)))
        print "Назначен уровень $n. ".$cnt." пользователям: ".implode(', ', $userIds)."<br />";
      if (Config::getVarVar('level', 'avatars')) {
        $oLA = new LevelAvatar();
        foreach ($userIds as $userId)
          $oLA->generateByUser($userId);
      }
      foreach ($userIds as $userId) {
        $oSender->send(
          $userId,
          "Вы получили уровень $n на сайте ".SITE_TITLE,
          Tt::getTpl('notify/msgs/level_nominate', array(
            'level' => $n,
            'userId' => $userId
          ))
        );
      }
      $n--;      
    }
  } 
  
}