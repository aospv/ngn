<?php

class PageMetatagsItemsManager extends DbItemsManager {
  
  public $pageId;

  public function __construct($pageId) {
    $this->pageId = $pageId;
    $this->oForm = new PageMetatagsForm();
    $this->oItems = new PageMetatagsItems();
  }
  
  protected function replaceData(&$data) {
    $data['pageId'] = $this->pageId;
  }
  
}
