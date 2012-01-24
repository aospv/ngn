<?php

require_once VENDORS_PATH.'/pheanstalk/pheanstalk_init.php';

class NgnQueueCore {

  const TUBE_NAME = 'ngn';
  
  /**
   * @return Pheanstalk
   */
  static public function getPheanstalk() {
    static $oP;
    if (isset($oP)) return $oP;
    $oP = new Pheanstalk(
      '127.0.0.1',
      Pheanstalk::DEFAULT_PORT,
      null
    );
    return $oP;
  }
  
  static public function addJob($class, $method, array $data = array()) {
    //LogWriter::str('queueJob', 'added');
    return self::getPheanstalk()->useTube(self::TUBE_NAME)->put(array(
      'projectName' => SITE_DOMAIN,
      'projectKey' => PROJECT_KEY,
      'class' => $class,
      'method' => $method,
      'data' => $data
    ), Pheanstalk::DEFAULT_PRIORITY, 0, 7200 /* 2h */);
  }
  
}