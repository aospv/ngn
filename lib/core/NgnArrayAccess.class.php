<?php

class NgnArrayAccess implements ArrayAccess {

  public $r = array();

  public function offsetSet($offset, $value) {
    if (is_null($offset)) {
      $this->r[] = $value;
    } else {
      $this->r[$offset] = $value;
    }
  }
  
  public function offsetExists($offset) {
    return isset($this->r[$offset]);
  }
  
  public function offsetUnset($offset) {
    unset($this->r[$offset]);
  }
  
  public function offsetGet($offset) {
    return isset($this->r[$offset]) ? $this->r[$offset] : null;
  }

}