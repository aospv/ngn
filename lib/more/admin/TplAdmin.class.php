<?php

class TplAdmin {
  
  function dateStr($name) {
    
  }
  
  static function getUserPath($id) {
    return Tt::getPath(1).'/users/?a=edit&id='.$id;
  }
  
}
