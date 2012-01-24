<?php

class Mysql {
	
  static public $mysqlPath = 'mysql';
  static public $mysqlAdminPath = 'mysqladmin';
  static public $mysqlDumpPath = 'mysqldump';

  /**
   * Переименовывает БД
   */
  static public function renameDb($rootUser, $rootPass, $rootHost, $from, $to) {
    if ($from == $to)
      throw new NgnException('Can\'t replace DB "'.$to.'" with itself');
    $uph = " -u$rootUser -p$rootPass -h$rootHost";
    //sys(self::$mysqlAdminPath.$uph.' -f drop '.$to);
    sys(self::$mysqlAdminPath.$uph.' create '.$to);
    sys(self::$mysqlDumpPath.$uph.' --default-character-set=utf8 '.$from.' | '.
      self::$mysqlPath.$uph .' '.$to.'');
    sys(self::$mysqlAdminPath.$uph.' -f drop '.$from);
  }
  
  static public function dump($rootUser, $rootPass, $rootHost, $dbName, $file) {
    $uph = " -u$rootUser -p$rootPass -h$rootHost";
    sys(self::$mysqlDumpPath.$uph." -f $dbName > $file");
  }

}
