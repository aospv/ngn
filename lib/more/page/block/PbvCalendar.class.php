<?php

class PbvCalendar extends PbvAbstract {

  static public $cachable = false;

  public function _html() {
    if (!PageControllersCore::hasAncestor($this->oCC->page['controller'], 'items')) return;
    if ($this->oCC->action != 'list') return;
    return Tt::getTpl('common/calendar', $this->oCC->d);
  }

}
