<?php

class DdTagsTagsFlat extends DdTagsTagsBase {
  
  public function getByTitle($title) {
    return db()->selectRow(
      'SELECT * FROM tags WHERE strName=? AND groupName=? AND title=?', 
      $this->oTG->getStrName(), $this->oTG->getName(), $title);
  }

  public function getByName($name) {
    return db()->selectRow(
    'SELECT * FROM tags WHERE strName=? AND groupName=? AND name=?', 
    $this->oTG->getStrName(), $this->oTG->getName(), $name);
  }

  public function getTags() {
    $r = db()->query('
    SELECT * FROM tags WHERE strName=? AND groupName=? ORDER BY oid', 
    $this->oTG->getStrName(), $this->oTG->getName());
    return $r;
  }
  
  protected $importSeparator = ',';
  
  public function setImportSeparator($s) {
    $this->importSeparator = $s;
  }
  
  public function import($text) {
    foreach (explode($this->importSeparator, $text) as $v)
      $titles[] = trim($v);
    for ($i = 0; $i < count($titles); $i++)
      $this->create(array(
        'title' => $titles[$i],
        'oid' => ($i+1)*10
      ));
  }
  
  public function getData() {
    return $this->getTags();
  }
  
}