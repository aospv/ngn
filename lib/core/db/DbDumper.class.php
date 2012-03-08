<?php

class DbDumper {

  public $isDroptables = false;

  public $isDumpStructure = true;

  public $isDumpData = true;

  public $autoIncrementToNull = true;
  
  /**
   * @var Db
   */
  private $oDb;
  
  public function __construct($oDb) {
    $this->oDb = $oDb;
  }

  // If set to true, it will generate 'DROP TABLE IF EXISTS'-statements for each table. 
  public function setDroptables($state) {
    $this->isDroptables = $state;
  }

  private function isDroptables() {
    return $this->isDroptables;
  }

  public $excludeTables;

  public $excludeRule;

  public $includeRule;
  
  public $includeTables;
  
  public $recordsLimit = 0; // 0 - без лимита
  
  private function filterTables(&$tables) {
    $_tables = array();
    foreach ($tables as $t) {
      if (isset($this->excludeTables) and in_array($t, $this->excludeTables))
        continue;
      if (isset($this->includeTables) and !in_array($t, $this->includeTables))
        continue;
      if (isset($this->excludeRule) and preg_match('/' . $this->excludeRule . '/', 
        $t))
        continue;
      if (isset($this->includeRule) and !preg_match('/' . $this->includeRule . '/', 
        $t))
        continue;
      $_tables[] = $t;
    }
    $tables = $_tables;
  }
  
  public $insertGroupLimit = 10;
  
  public $separateGroupFiles = false;

  /**
   * Делает дамп базы
   *
   * @param   array   Таблицы для дампа или null, если нужен дамп всех таблиц базы
   * @return  string  Дамп
   */
  public function createDump($toFile, $onlyTables = null) {
    // Set line feed 
    $lf = "\n";
    $result = mysql_query("SHOW TABLES", $this->oDb->link) or die(mysql_error());
    $tables = $this->result2Array(0, $result);
    $this->filterTables($tables);
    foreach ($tables as $tblval) {
      $result = mysql_query("SHOW CREATE TABLE `$tblval`");
      $createtable[$tblval] = $this->result2Array(1, $result);
    }
    if (file_exists($toFile)) unlink($toFile);
    if (!$fp = fopen($toFile, 'a'))
      throw new NgnException('Can not open file "'.$toFile.'"');
    // Set header
    $dumpHeader = "#" . $lf;
    $dumpHeader .= "# DbDumper SQL Dump" . $lf;
    $dumpHeader .= "# Version 1.0" . $lf;
    $dumpHeader .= "# " . $lf;
    $dumpHeader .= "# Host: " . $this->oDb->getHost() . $lf;
    $dumpHeader .= "# Generation Time: " . date("M j, Y \\a\\t H:i") . $lf;
    $dumpHeader .= "# Server version: " . mysql_get_server_info() . $lf;
    if ($this->oDb->getName())
      $dumpHeader .= "# Database : `" . $this->oDb->getName() . "`" . $lf;
    $dumpHeader .= "#";
    $nn = 0;
    fwrite($fp, $dumpHeader);
    $tablesN = 0;
    $groupN = 1;
    // Generate dumptext for the tables. 
    foreach ($tables as $tblval) {
      $tablesN++;
      if (isset($onlyTables) and ! in_array($tblval, $onlyTables)) {
        continue;
      }
      $tableHeader = $lf . $lf . "# --------------------------------------------------------" . $lf . $lf;
      if ($this->isDumpStructure) {
        if ($this->autoIncrementToNull)
          $createtable[$tblval][0] = preg_replace(
            '/AUTO_INCREMENT=\d+ / ', '', 
            $createtable[$tblval][0]);
        $tableHeader .= "#" . $lf . "# Table structure for table `$tblval`" . $lf;
        $tableHeader .= "#" . $lf . $lf;
        // Generate DROP TABLE statement when client wants it to. 
        if ($this->isDroptables()) {
          $tableHeader .= "DROP TABLE IF EXISTS `$tblval`;" . $lf;
        }
        $tableHeader .= $createtable[$tblval][0] . ";" . $lf;
        $tableHeader .= $lf;
        output("Table '$tblval' structure exported");
      }
      fwrite($fp, $tableHeader);
      
      if ($this->isDumpData) {
        output('Dumping data for '.$tblval.' table');
        // ------------------------------------------
        $tableDumpHeader = "#" . $lf . "# Dumping data for table `$tblval`" . $lf . "#" . $lf;
        fwrite($fp, $tableDumpHeader);
        
        $emptifyFieldNames = array();
        if (isset($this->emptifyFieldTypes)) {
          // Имена полей, значение которых нужно будет заменить на пустые строки
          foreach ($this->oDb->colTypes($tblval) as $fieldName => $fieldType)
            if (in_array($fieldType, $this->emptifyFieldTypes))
              $emptifyFieldNames[] = $fieldName;
        }
        
        $orderCond = in_array('id', $this->oDb->cols($tblval)) ? ' ORDER BY id DESC' : '';
        $limitCond = $this->recordsLimit ? ' LIMIT '.$this->recordsLimit : '';
        $q = "SELECT * FROM `$tblval`".$orderCond.$limitCond;
        $result = mysql_query($q);
        if ($result === false) Err::sqlDie(mysql_error());
        $rowN = 0;
        $insertdumpGroup = '';
        
        $nn = 0; // Общий счетчик количества экспортируемых записей по текущей таблице
        while (($row = mysql_fetch_assoc($result))) {
          if ($rowN == 0)
            $insertdumpGroup = $lf . "INSERT INTO `$tblval` VALUES";
          $insertdump = $lf . "(";
          $arr = $row;
          foreach ($arr as $fieldName => $value) {
            if (!empty($emptifyFieldNames) and in_array($fieldName, $emptifyFieldNames)) {
              $value = 'dummy';
            } else {
              $value = addslashes($value);
              $value = str_replace("\n", '\r\n', $value);
              $value = str_replace("\r", '', $value);
            }
            $insertdump .= is_numeric($value) ? "$value, " : "'$value', ";
          }
          $rowN++;
          $nn++;
          if ($rowN == $this->insertGroupLimit) {
            $insertdump = rtrim($insertdump, ', ') . ");";
            $insertdumpGroup .= $insertdump;
            if ($this->separateGroupFiles) {
              fclose($fp);
              $fp = fopen($this->getFilename($toFile, $groupN), 'w');
            }
            fwrite($fp, $insertdumpGroup);
            $insertdumpGroup = '';
            $rowN = 0;
            $groupN++;
          } else {
            $insertdump = rtrim($insertdump, ', ') . "),";
            $insertdumpGroup .= $insertdump;
          }
        }
        if ($insertdumpGroup) {
          if ($this->separateGroupFiles) {
            fclose($fp);
            $fp = fopen($this->getFilename($toFile, $groupN), 'w');
          }
          $insertdumpGroup = rtrim($insertdumpGroup, ',') . ";";
          fwrite($fp, $insertdumpGroup);
        }
        if ($nn == 0)
          output("There is no data to dump in table '$tblval'");
        else
          output("Table '$tblval' data exported ($nn records)");
      }
    }
    fclose($fp);
  }
  
  private function getFilename($filename, $n) {
    return preg_replace('/(\w+)(\.\w+)/', '$1-'.sprintf('%04d', $n).'$2', $filename);
  }
  
  private function object2Array($obj) {
    $array = null;
    if (is_object($obj)) {
      $array = array();
      foreach (get_object_vars($obj) as $key => $value) {
        if (is_object($value))
          $array[$key] = $this->object2Array($value);
        else
          $array[$key] = $value;
      }
    }
    return $array;
  }

  private function loadObjectList($key = '', $resource) {
    $array = array();
    while (($row = mysql_fetch_object($resource))) {
      if ($key)
        $array[$row->$key] = $row;
      else
        $array[] = $row;
    }
    mysql_free_result($resource);
    return $array;
  }

  private function result2Array($numinarray = 0, $resource) {
    $array = array();
    while (($row = mysql_fetch_row($resource))) {
      $array[] = $row[$numinarray];
    }
    mysql_free_result($resource);
    return $array;
  }
  
  protected $emptifyFieldTypes;
  
  public function setEmptifyFieldTypes(array $types) {
    $this->emptifyFieldTypes = $types;
  }

}