<?php

class DbModelTags extends DbModel {

  static public function beforeCreateUpdate(array &$data) {
    if (empty($data['name']) and !empty($data['title']))
      $data['name'] = DdTags::title2name($data['title']); 
  }

}