<?php

class PageModules {

  public $items;
	
  public function __construct() {
    $this->items = array();
    foreach (ClassCore::getDescendants('Pmi', 'Pmi') as $v) {
      $o = O::get($v['class']);
      $this->items[$v['name']] = array(
        'title' => $o->title,
        'controller' => $o->controller,  
        'oid' => empty($o->oid) ? 9999 : $o->oid
      );
    }
    $this->items = Arr::sort_by_order_key($this->items, 'oid');
  }

  public function getItems() {
    return $this->items;
  }

}
