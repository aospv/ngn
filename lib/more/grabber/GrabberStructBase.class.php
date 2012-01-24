<?php

class GrabberStructBase {
  
  public function getFields() {
    return array(
      'title' => array(
        'title' => 'Название', 
        'type' => 'text', 
        'required' => true
      ),
      'url' => array(
        'title' => 'Ссылка', 
        'type' => 'url',
        'required' => true
      )      
    );
  }
  
  public function  getVisibilityConditions() {
    return array();
  }
  
}
