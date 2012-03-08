<?php

function logSql($db, $sql) {
  if (!($sqlData = R::get('sqlData'))) $sqlData = array();
  if (!preg_match('/^\s*--.*/', $sql)) {
    R::increment('sqlN');
    $sqlData[] = array(
      'sql' => $sql,
      'backtrace' => getBacktrace()
    );
    R::set('sqlData', $sqlData);
  } else {
    if (isset($sqlData[count($sqlData)-1])) {
      $sqlData[count($sqlData)-1]['info'] = $sql;
      R::set('sqlData', $sqlData);
    }
  }
  LogWriter::html('sql', $sql);
}

class DbSite extends Db {
  
  public $blockModification = false;

  protected function _query($querys, &$total) {
    if ($this->blockModification) {
      foreach ($querys as $q)
        if (preg_match('/^(UPDATE|REPLACE|INSERT|DELETE) .*/i', $q))
          return;
    }
    return parent::_query($querys, $total);
  }  
  
  public function __construct() {
    if (!defined('DB_USER')) Config::loadConstants('database');
    parent::__construct(DB_USER, DB_PASS, DB_HOST, DB_NAME, DB_CHARSET);
    // Блокирует модификацию базы
    if (defined('DB_BLOCK_MODIF') and DB_BLOCK_MODIF === true)
      $this->blockModification = true;
    // Определяет префикс
    if (defined('DB_PREFIX'))
      $this->setIdentPrefix(DB_PREFIX.'_');
    // Определяет ф-ю для логирования запросов
    if (DB_LOGGING === true) $this->setLogger('logSql');
  }
  
  
}
