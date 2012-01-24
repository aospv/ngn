<?php

class CtrlAdminLogs extends CtrlAdmin {

  static $properties = array(
    'title' => 'Логи',
    'order' => 310,
    'onMenu' => true
  );

  protected $logName;
  
  protected function init() {
    $this->d['logs'] = LogReader::logs();
    if (empty($this->d['logs'])) throw new NgnException('There is no logs');
    if (isset($this->params[2]) and in_array($this->params[2], $this->d['logs'])) {
      $this->logName = $this->params[2];
    } else {
      $this->logName = Arr::first($this->d['logs']);
    }
  }

  public function action_default() {
    $this->d['logName'] = $this->logName;
    $this->d['items'] = LogReader::get($this->logName);
    $this->d['tpl'] = 'logs/default';
  }
  
  public function action_cleanup() {
    LogReader::cleanup($this->logName);
    $this->redirect();
  }
  
  public function action_delete() {
    LogReader::delete($this->logName);
    $this->redirect();
  }
  
}