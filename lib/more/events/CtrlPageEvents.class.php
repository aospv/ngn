<?php

abstract class CtrlPageEvents extends CtrlPage {
  
  public $oEvents;
  
  function init() {
    $this->oEvents = new Events();
  }
  
  function action_default() {
    $this->d['tpl'] = 'events/default';
    $this->d['items'] = $this->oEvents->getEvents();
    $this->d['pagination']['pNums'] = $this->oEvents->pNums;
  }
  
}