<?php

require_once VENDORS_PATH.'/DbSimple/Mysql.php';

class Db extends DbSimple_Mysql {
  
  private $user;
  private $pass;
  private $host;
  private $name;
  public $charset;
  public $collate = 'utf8_general_ci';
  
  public function __construct($user, $pass, $host, $name, $charset = 'utf8') {
    $this->user = $user;
    $this->pass = $pass;
    $this->host = $host;
    $this->name = $name;
    parent::__construct('mysql://' . $user . ':' . $pass . '@' . $host . '/' . $name);
    // Определяет ф-ю для логирования ошибок
    $this->setErrorHandler(array('Err', 'sql'));
    $this->charset = $charset;
    $this->query('SET NAMES '.$charset);
  }
  
  public function create($table, array $data, $replace = false) {
    return $this->query(($replace ? 'REPLACE' : 'INSERT')." INTO $table SET ?a", $data);
  }
  
  public function getHost() {
    return $this->host;
  }
    
  public function getName() {
    return $this->name;
  }
  
  public function q($q) {
    return $this->select($q);
  }  

  public function row($q) {
    return $this->selectRow($q);
  }

  public function col($q) {
    return $this->selectCol($q);
  }

  public function cell($q) {
    return $this->selectCell($q);
  }
  
  /**
   * Возвращает массив с именами всех таблиц NGN
   */
  static public function _tables($name, $link) {
    // Определить является ли таблица, таблицой NGN
    // А всё просто. Если есть префикс, значит все с префиксом.
    // Если нет префикса, то тогда просто все...
    $tables = array();
    $r = mysql_query('SHOW FULL TABLES FROM '.$name, $link);
    while (($row = mysql_fetch_row($r))) {
      if ($row[1] != 'VIEW') $tables[] = $row[0];
    }
    return $tables;
  }
  
  public function tables() {
  	return $this->_tables($this->name, $this->link);
  }
  
  public function cols($table) {
    return $this->selectCol("
    SELECT COLUMN_NAME FROM information_schema.`COLUMNS`
    WHERE TABLE_SCHEMA=? AND TABLE_NAME=?", $this->name, $table);
  }
  
  public function colTypes($table) {
    return $this->selectCol("
    SELECT COLUMN_NAME AS ARRAY_KEY, DATA_TYPE
    FROM information_schema.`COLUMNS`
    WHERE TABLE_SCHEMA=? AND TABLE_NAME=?", $this->name, $table);
  }

  public function ddTables() {
    $tables = array();
    foreach ($this->tables() as $table) {
      if (preg_match('/^dd_i_.*?/', $table))
        $tables[] = $table;
    }
    return $tables;
  }
  
  public function fieldExists($table, $name) {
    return in_array($name, $this->fields($table));
  }

  public function fields($table) {
    return $this->selectCol('SHOW COLUMNS FROM ' . $table);
  }

  public function rename($from, $to) {
    return $this->query("RENAME TABLE `$from` TO `$to`");
  }
  
  public function renameField($table, $from, $to) {
    $types = Arr::get($this->query("SHOW COLUMNS FROM $table"), 'Type', 'Field');
    $this->query("ALTER TABLE `$table` CHANGE `$from` `$to` {$types[$from]}");
  }
  
  public function replace($from, $to) {
    $this->query("DROP TABLE IF EXISTS $to");
    return $this->query("RENAME TABLE `$from` TO `$to`");
  }

  public function exists($table) {
    return in_array($table, $this->tables());
  }

  public function dbExists($db) {
    $dbList = mysql_list_dbs($this->link);
    $i = 0;
    $cnt = mysql_num_rows($dbList);
    while ($i < $cnt) {
      if (mysql_db_name($dbList, $i) == $db)
        return true;
      $i++;
    }
    return false;
  }

  public function copy($from, $to) {
    $this->query("DROP TABLE IF EXISTS $to");
    $this->query("CREATE TABLE $to LIKE $from");
    $this->query("INSERT INTO $to SELECT * FROM $from");
  }
  
  public function copyStructure($from, $to) {
    $this->query("DROP TABLE IF EXISTS $to");
    $this->query("CREATE TABLE $to LIKE $from");
  }
  
  public function copyPrefixed($prefix) {
    foreach ($this->tables() as $t)
      $this->copy($t, $prefix.'_'.$t);
  }
  
  public function deletePrefixed($prefix) {
    foreach ($this->tables() as $t)
      if (preg_match('/^' . $prefix . '_.*/', $t))
        $this->query("DROP TABLE $t");
  }
  
  public function insert($table, array $data) {
    return $this->query("INSERT INTO $table SET ?a", $data);
  }
  
  public function insertLarge($table, $rows) {
    if (empty($rows)) throw new NgnException('$rows is empty');
    $keys = array_keys($rows[0]);
    if ($keys[0] == 0) throw new Exception('First element of $rows must be a hash');
    $q = 'INSERT INTO '.$table.' ('.implode(', ', array_keys($rows[0])).") VALUES \n";
    foreach ($rows as $row) {
      array_walk($row, 'quoting');
      $q .= '('.implode(', ', $row)."),\n";
    }
    $q[strlen($q)-2] = ';';
    $this->query($q);
  }
  
  public function insertLargeFull($table, $rows) {
    $q = 'INSERT INTO '.$table.' ('.implode(', ', $this->cols($table)).") VALUES \n";
    foreach ($rows as $row) {
      array_walk($row, 'quoting');
      $q .= '('.implode(', ', $row)."),\n";
    }
    $q[strlen($q)-2] = ';';
    $this->query($q);
  }
  
  //=============================================================
  
  public function backup() {
    foreach ($this->tables() as $table) {
      if (! strstr($table, 'bak_')) {
        //prrr('Copy DB "'.$table.'" --> "'.('bak_'.$table).'"');
        $this->copy($table, 
          'bak_' . $table);
      }
    }
  }

  public function restore() {
    foreach ($this->tables() as $table) {
      if (strstr($table, 'bak_')) {
        $this->copy($table, 
          str_replace('bak_', '', $table));
      }
    }
    $this->deleteBackup();
  }

  public function deleteBackup() {
    foreach ($this->tables() as $table) {
      if (strstr($table, 'bak_'))
        $this->query("DROP TABLE $table");
    }
  }

  /**
   * Возвращает строку с дампом
   *
   * @param   null/array null, если нужно экспортировать все таблицы или массив 
   *          с именами таблиц елси нужно экспортировать только конкретные
   * @return  string
   */
  public function export($toFile, $tables = null) {
    $oDbDumper = new DbDumper($this);
    $oDbDumper->setDroptables(true);
    $oDbDumper->insertGroupLimit = 10;
    return $oDbDumper->createDump($toFile, $tables);
  }
  
  private $importLogger;
  
  public function setImportLogger($importLogger) {
    $this->importLogger = $importLogger;
  }
  
  public $importFileSizeLimit = 0;

  public function importFile($file) {
    sys("mysql -h{$this->host} -u{$this->user} -p{$this->pass} --default_character_set utf8 {$this->name} < $file");
  }
  
  public function importFile___OLD($file) {
    $this->setErrorHandler(array('Err', 'sqlDie'));
    $fp = fopen($file, 'r');
    $q = '';
    $bytes = 0;
    while (($c = fread($fp, 512)) !== false) {
      $bytes += 1024;
      if ($this->importFileSizeLimit and $bytes > $this->importFileSizeLimit) {
        $q = '';
        break;
      }
      print '';
      $c = str_replace("\r", '', $c);
      if (strstr($c, ";\n")) {
        $querys = explode(";\n", $c);
        $querys[0] = $q . $querys[0];
        $q = count($querys) > 1 ? $querys[count($querys)-1] : '';
        for ($i=0; $i<count($querys)-1; $i++) {
          $this->import($querys[$i]);
        }
      } else {
        $q .= $c;
      }
    }
    if ($q != '')
      $this->import($q);
    $this->setErrorHandler(array('Err', 'sql'));
  }
  
  public function import($sql) {
    $startTime = getMicrotime();
    $sql = str_replace("\r", '', $sql);
    $sql = trim(preg_replace('/^\s*#.*$\n/m', '', $sql));
    $sql = trim(preg_replace('/^\s*--.*$\n/m', '', $sql));
    $disabledKeysTables = array();
    foreach (explode(";\n", $sql) as $query) {
      if (trim($query)) {
        if ($this->importLogger and is_callable($this->importLogger))
          call_user_func($this->importLogger, $query);
        /*
        if (preg_match('/INSERT\s+INTO\s+`*([^\s^`]*)`* /', $query, $m)) {
          $table = $m[1];
          if (!in_array($table, $disabledKeysTables)) {
            $this->q("ALTER TABLE $table DISABLE KEYS");
            $disabledKeysTables[] = $table;
          }
        }
        */
        mysql_query($query, $this->link);
        // $this->q($query); // почему то вызывает рекурсивный вызов себя
      }
    }
    /*
    foreach ($disabledKeysTables as $table) {
      $this->q("ALTER TABLE $table ENABLE KEYS");
    }
    */
    $processTime = round(getMicrotime() - $startTime, 3);
    $this->log("Import time: $processTime sec.");
  }
  
  function log($t) {
    if (isset($this->logger) and is_callable($this->logger))
      call_user_func($this->_logger, $t);
  }
  
  public function delete($tables = null) {
    $tables = (array)$tables;
    if ($tables)
      $this->log("Delete tables: ".implode(', ', $tables)." in database ".$this->name);
    else $this->log("Delete all tables in database ".$this->name);
    foreach ($this->tables() as $table) {
      if ($tables and !in_array($table, $tables))
        continue;
      $this->query("DROP TABLE $table");
    }
  }
  
  public function deleteCol($table, $colName, $strict = false) {
    if (!$strict and !in_array($colName, $this->cols($table))) return;
    $this->query("ALTER TABLE $table DROP $colName");
  }
  
  public function setPrefix($prefix) {
    foreach ($this->tables() as $t)
      $this->rename($t, $prefix.'_'.$t);
  }
  
  public function removePrefix($prefix) {
    foreach ($this->tables() as $t)
      $this->rename($t, str_replace($prefix.'_', '', $t));
  }
  
  public function ids($table, $cond = null) {
    if (!$cond)
      return $this->selectCol("SELECT id FROM ".$table);
    else
      return $this->selectCol("SELECT $table.id FROM ".$table.$cond->all());
  }
  
  public function firstId($table) {
    return $this->selectCell("SELECT id FROM $table ORDER BY id LIMIT 1");
  }
  
  public function prepareQuery($query, array $_args = array()) {
    if (count($_args) > 1) {
      $args[0] = $query;
      $args = Arr::append($args, $_args);
      $this->_expandPlaceholders($args);
      $query = $args[0];
    }
    return $query;
  }
  
  public function getAndCond($params) {
    if (!count($params)) return '1';
    return Tt::enum($params, ' AND ', "\\\"\$k=`\$v`\\\""); // Шаблон преобразовывается в "$k='$v'"
  }
  
  public function unpack($query) {
    $rows = $this->query($query);
    foreach ($rows as &$row) {
      foreach ($row as &$v) {
        if (Misc::unserializeble($v))
          $v = unserialize($v);
      }
    }
    return $rows;
  }
  
  public $multiInsertReplace = false;
  
  public function multiInsertAddIdColumn($table, $data, $rowsPerQuery = 200) {
    $n=0;
    $id = $this->selectCell("SELECT id FROM $table ORDER BY id DESC LIMIT 1");
    foreach ($data as $v) {
      $id++;
      $data2[$n]['id'] = $id;
      $data2[$n] += $v;
      $n++;
    }
    $this->multiInsert($table, $data2, $rowsPerQuery);
  }
  
  public function multiInsert($table, $data, $rowsPerQuery = 200) {
    $portion = array();
    for ($i=0; $i<count($data); $i++) {
      $portion[] = $data[$i];
      if (($i+1) % $rowsPerQuery == 0) {
        $this->_multiInsert($table, $portion);
        $portion = array();
      }
    }
    if ($portion)
      $this->_multiInsert($table, $portion);
  }
  
  protected function _multiInsert($table, $data) {
    if (!$data) return;
    $rows = ''; 
    foreach ($data as $row) {
      foreach ($row as &$v) $v = "'$v'";
      $rows[] = '('.implode(', ', $row).')';
    }
    $q = ($this->multiInsertReplace ? 'REPLACE' : 'INSERT').
      " INTO $table VALUES ".implode(', ', $rows).';';
    $this->query($q);
  }
  
  public function getCell($table, $field1, $field2, $value) {
    return db()->selectCell("SELECT $field1 FROM $table WHERE $field2='$value'");
  }
  
  // ------------------ static -------------------
  
  static public function createDb($user, $pass, $host, $name) {
    if (!mysql_connect($host, $user, $pass))
      throw new NgnException("Can not connect. User='$user', Pass='$pass', Host='$host'");
    mysql_query("CREATE DATABASE IF NOT EXISTS $name");
  }
  
  static public function deleteDb($user, $pass, $host, $name) {
    if (!mysql_connect($host, $user, $pass))
      throw new NgnException("Can not connect. User='$user', Pass='$pass', Host='$host'");
    mysql_query('DROP DATABASE '.$name);
  }
  
  static public function getReservedMySQLwords() {
    if (!($words = file(LIB_PATH.'/more/dd/reservedMySQLwords.txt')))
      throw new NgnException('Can not open "reservedMySQLwords.txt"');
    for ($i = 0; $i < count($words); $i++)
      $words[$i] = strtolower(trim($words[$i]));
    return $words;
  }
  
  static public function getReservedNames() {
    return Arr::append(
      Db::getReservedMySQLwords(),
      array(
        'dateCreate', 
        'dateUpdate', 
        'datePublish', 
        'commentsUpdate', 
        'pageId', 
        'ip', 
        'id', 
        'action', 
        'n'
      )
    );
  }
  
  static public function isReservedMySQLword($word) {
    return in_array(strtolower($word), Db::getReservedMySQLwords()) ? true : false;
  }
  
  static public function normalize($s) {
    $s = Misc::translate($s);
    $s = Misc::hyphenate($s);
    $s = str_replace(' ', '_', $s);
    return str_replace('-', '_', $s);
  }
  
  static public function getSize($dbName, Db $db = null) {
    if (!$db) $db = db();
    return round($db->selectCell("
SELECT
  SUM(DATA_LENGTH + INDEX_LENGTH) AS size
FROM information_schema.TABLES
WHERE table_schema = '$dbName'
GROUP BY TABLE_SCHEMA
"));
  }
  
  public function getNextId($table) {
    return $this->selectCell("SELECT id FROM $table ORDER BY id DESC");
  }
  
}
