<?php

class NgnClDaemonRandomPeriod extends NgnClDaemon {

  protected $requiredOptions = array('from', 'to'); // seconds

  protected function initNextN() {
    $this->nextTime = rand(
      $this->options['from']/$this->options['iterTime'],
      $this->options['to']/$this->options['iterTime']
    )*$this->options['iterTime'];
  }
  
  protected $lastTime;

  protected function iteration() {
    if (time() >= $this->nextTime) {
      $this->lastTime = time();
      $this->_iteration();
    }
  }
  
  protected function _iteration() {
    if (isset($this->options['action'])) $this->options['action']();
  }

}