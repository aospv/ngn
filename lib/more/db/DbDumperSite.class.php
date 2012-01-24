<?php

class DbDumperSite extends DbDumper {
  
  public function __construct() {
    parent::__construct(db());
  }
  
  public function exportOnlyDd($flag) {
    $this->includeRule = $flag ? 'dd_i_.*' : '';
  }

  public function exportDdItemsTables() {
  }

}