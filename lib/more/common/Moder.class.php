<?php

class Moder {
  
  static function getModerIds($pageId) {
    return Privileges::getUserIds($pageId, 'moder');
  }
  
  static function getModers($pageId) {
    if (!($ids = self::getModerIds($pageId))) return array();
    return DbModelCore::collection('users', DbCond::get()->addF('id', $ids));
  }
  
}
