<?php

class FieldCore {

  const FIELD_ELEMENT_CLASS_PREFIX = 'FieldE';
  
  /**
   * @return FieldEAbstract
   */
  static public function get($type, array $options = array(), FormBase $oForm = null) {
    return O::get(self::FIELD_ELEMENT_CLASS_PREFIX.ucfirst($type), $options, $oForm);
  }
  
  static public function getClass($type) {
    return self::FIELD_ELEMENT_CLASS_PREFIX.ucfirst($type);
  }
  
  static public function isInput($type) {
    return self::hasAncestor($type, 'input');
  }
  
  static public function hasAncestor($type, $ancestorType) {
    return ClassCore::hasAncestor(self::getClass($type), self::getClass($ancestorType));
  }
  
}
