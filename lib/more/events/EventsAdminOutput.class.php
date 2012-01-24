<?php

class EventsAdminOutput {
  
  /**
   * Event to group array
   *
   * @var array
   */
  protected $eventGroup = array(
    'updateItem' => 'dd',
    'createItem' => 'dd',
    'deleteItem' => 'dd'
  );
  
  protected $items;
  
  public function __construct($items) {
    $this->items = $items;
  }
  
  public function editLink($id) {
    
  }
  
  public function viewLink($id) {
    
  }
  
  public function viewTitle($id) {
    return '"'.$v['data']['page']['title'].'"/'.
           $v['data']['title'] ? $v['data']['title'] : $v['data']['id'];    
  }
  
  public function viewPath($id) {
  }
  
}