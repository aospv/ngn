<?php

class ProcessTime {
  
  static public function start() {
    R::set('processTimeStart', getMicrotime());
  }
  
  static public function end() {
    return round(getMicrotime() - R::get('processTimeStart'), 3);
  }
  
}
