<?php

class PageModulesAllowed extends PageModules {

  public function __construct() {
    parent::__construct();
    $this->items = Arr::filter_by_keys($this->items,
      Config::getVarVar('adminPriv', 'allowedPageModules'));
  }

}
