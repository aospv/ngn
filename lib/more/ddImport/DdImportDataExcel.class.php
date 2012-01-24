<?php

class DdImportDataExcel extends DdImportDataReceiver {

  protected $excelFile;
  
  public function __construct(DdImportField $oF, $excelFile) {
    parent::__construct($oF);
    $this->excelFile = $excelFile;
  }
  
  public function getData() {
    $oSER = new Spreadsheet_Excel_Reader();
    $oSER->setOutputEncoding(CHARSET);
    $oSER->read($this->excelFile);
    $r = array();
    foreach ($oSER->sheets[0]['cells'] as $k => $row) {
      if ($k == 1) continue; // Первая строка - шапка таблицы
      $n=0;
      $row = array();
      foreach (array_keys($this->fieldTypes) as $fieldName) {
        $n++;
        $row[$fieldName] = $oSER->sheets[0]['cells'][$k][$n];
      }
      $r[] = $row;
    }
    return $r;
  }
  
}
