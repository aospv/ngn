<?php


class DdImporter {
  
  static $oIs;
  
  static $dir = 'dd-import';
  
  static $sampleLimit = 5;
  
  /**
   * Импортирует данные из файла, загруженного для Данного раздела
   *
   * @param   string  Имя структуры
   * @param   integer ID Данного раздела
   * @param   array   Имя поля - Номенр колонки
   */
  static function import($pageId, $fieldName2colN) {
    foreach (ExcelTableData::getData(self::getFilename($pageId)) as $line) {
      foreach ($fieldName2colN as $fieldName => $N) {
        $d[$fieldName] = $line[$N];
      }
      $data[] = $d;
    }
    self::importByData($pageId, $data);    
  }
  
  /**
   * Импортирует записи из массива
   * array(
   *   array(
   *     fieldName => value
   *   )
   * )
   *
   * @param   integer   ID раздела
   * @param   array     массив с данными для записей
   */
  static function importByData($pageId, $data) {
    $strName = Pages::getStrName($pageId);
    $oIM = new DdItemsManager(
      new DdItems($pageId),
      new DdForm(new DdFields($strName), $pageId)
    );
    foreach ($data as $v) {
      $oIM->create($v);
    }
  }

  static function saveFile($file, $pageId) {
    if (!$pageId) throw new NgnException('$pageId not defined');
    $dirPath = DATA_PATH.'/'.self::$dir.'/';
    Dir::make($dirPath);
    copy($file, self::getFilename($pageId));
  }
  
  static function getFilename($pageId) {
    return DATA_PATH.'/'.self::$dir.'/'.$pageId;
  }
  
  static function fileExists($pageId) {
    return file_exists(self::getFilename($pageId)) ?
           self::getFilename($pageId) : false;
  }
  
  static function getSampleData($pageId) {
    $data = array();
    $filpath = self::getFilename($pageId);
    if (file_exists($filpath)) {
      $n = 0;
      foreach (ExcelTableData::getData($filpath) as $k => $v) {
        $data[$k] = $v;
        $n++;
        if ($n == self::$sampleLimit) break;
      }
    }
    return $data;
  }
  
}


class Csv {
  
  static function getData($pageId) {
    return self::getFileData(DATA_PATH.'/'.self::$dir.'/'.$pageId, 0, ';');
  }
  
  static function getSampleData($pageId) {
    return self::getFileData(DATA_PATH.'/'.self::$dir.'/'.$pageId,
      self::$sampleLimit, ';');
  }
  
  static function getFileData($file, $limit = 0, $delimiter = ',') {
    $lines = array();
    $n = 0;
    foreach (file($file) as $line) {
      $line = trim($line);
      $line = explode($delimiter, $line);
      foreach ($line as &$v) {
        if ($v[0] == '"') $v = substr($v, 1, strlen($v));
        if ($v[strlen($v)-1] == '"') $v = substr($v, 0, strlen($v)-1);
      }
      $lines[] = $line;
      $n++;
      if ($limit and $n == $limit) break;
    }
    return $lines;
  }

  static function getCsv($text, $limit = 0, $delimiter = ',') {
    $lines = array();
    $n = 0;
    foreach (explode("\n", $text) as $line) {
      $line = trim($line);
      $line = explode($delimiter, $line);
      foreach ($line as &$v) {
        if ($v[0] == '"') $v = substr($v, 1, strlen($v));
        if ($v[strlen($v)-1] == '"') $v = substr($v, 0, strlen($v)-1);
      }
      $lines[] = $line;
      $n++;
      if ($limit and $n == $limit) break;
    }
    return $lines;
  }  
  
}


