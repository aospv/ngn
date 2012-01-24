<?php

class CtrlAdminEvents extends CtrlAdmin {

  static $properties = array(
    'title' => 'События',
    'onMenu' => true,
    'order' => 300
  );
  
  /**
   * @var Events
   */
  public $oEvents;
  
  function init() {
    $this->oEvents = new Events();
    $this->oEvents->n = 30;
  }
  
  function action_default() {
    $this->d['items'] = $this->oEvents->getItems();
    //die2($this->d['items']);
    $this->d['pagination']['pNums'] = $this->oEvents->pNums;
    $this->d['tpl'] = 'events/default';
  }
  
  function action_deleteAll() {
    $this->oEvents->deleteAll();
    $this->redirect();
  }
  
}