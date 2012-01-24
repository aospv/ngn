<?php

class CtrlCommonClearTpl extends CtrlCommon {

  protected function init() {
    $this->hasOutput = false;
  }
  
  public function action_default() {
    Tt::tpl('clearTpl', array('tpl' => 'clearTpl/'.$this->getParam(2)));
  }
  
  public function action_json_asd() {
    return new Form(new Fields(array(
      array(
        'title' => 'dqqdw',
        'type' => 'wisiwigSimple',
        'name' => 'asd',
      )
    )));
  }
  
}
