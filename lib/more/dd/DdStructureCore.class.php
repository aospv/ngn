<?php

class DdStructureCore {

  static public function getTypes() {
    return array(
      'dynamic' => 'Динамическая',
      'static' => 'Статическая',
      'variant' => 'Любая'
    );
  }
  
  static public function getDefaultFields($type) {
    if ($type != 'static' and $type != 'variant') return array();
    return array(array(
      'name' => 'static_id',
      'title' => 'static_id',
      'type' => 'num',
      'system' => 1, 
      'editable' => 0,
      'virtual' => 1,
      'notList' => 1 
    ));
  }

}