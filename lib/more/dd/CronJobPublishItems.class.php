<?php

class CronJobPublishItems extends CronJobAbstract {
  
  public $period = 'hourly';
  
  public function _run() {
    foreach (db()->ddTables() as $table) {
      db()->query(
        "UPDATE $table SET active = 1 WHERE datePublish < ? AND active = 0",
        date('Y-m_d H:i:s'));
    }
  }
  
}