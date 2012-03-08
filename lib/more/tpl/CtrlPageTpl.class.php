<?php

class CtrlPageTpl extends CtrlPage {
  
  public $requiredSettings = array('tplName');

  function action_default() {
    $this->d['tpl'] = 'tpl/'.($this->page['settings']['tplName'] ? $this->page['settings']['tplName'] :
      $this->page['name']);
  }

}