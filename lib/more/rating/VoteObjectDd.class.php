<?php

class VoteObjectDd implements VoteObject {
  
  public $strName;
  
  public function __construct($strName) {
    $this->strName = $strName;
  }
  
  public function vote($id, $n) {
    db()->query("UPDATE dd_i_{$this->strName} SET rating=rating+?d WHERE id=?d", $n, $id);
  }
  
}
