<?php

class DdoSiteFactory {
  
  /**
   * @param   array
   * @param   string
   * @return  Ddo
   */
  static public function get(DbModelPages $page, $layoutName) {
    if (($class = PageModuleCore::getClass($page->getModule(), 'DdoSpm')) !== false) {
      return O::get($class, $page, $layoutName);
    } else {
      return O::get('DdoSite', $page, $layoutName);
    }
  }
  
}
