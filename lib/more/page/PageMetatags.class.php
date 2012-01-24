<?php

class PageMetatags {
  
  static function create($pageId, $title, $description, $keywords) {
    $oPBI = new PageMetatagsItems();
    return $oPBI->create(array(
      'pageId' => $pageId,
      'title' => $title,
      'description' => $description,
      'keywords' => $keywords
    ));
  }
  
  static function get($pageId) {
    $oPBI = new PageMetatagsItems();
    $oPBI->cond->addF('pageId', $pageId);
    return $oPBI->getItems();
  }
  
  static function delete($id) {
    $oPBI = new PageMetatagsItems();
    $oPBI->delete($id);
  }
  
}
