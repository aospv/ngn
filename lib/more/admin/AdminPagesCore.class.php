<?php

class AdminPagesCore {

  static public function getEditContentSubController($controller) {
    $classes = array_map(
      function($v) {
        return 'SubPaAdminPages'.ucfirst($v);
      },
      ClassCore::getAncestorNames(ClassCore::nameToClass('CtrlPage', $controller), 'CtrlPage')
    );
    foreach ($classes as $class) if (O::exists($class)) return $class;
    return false;
  }

}