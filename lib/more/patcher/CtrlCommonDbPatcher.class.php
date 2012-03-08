<?php

class CtrlCommonDbPatcher extends CtrlCommonPatcher {
  
  protected function setPatcher() {
    $this->oPatcher = O::get('DbPatcher');
  }

}