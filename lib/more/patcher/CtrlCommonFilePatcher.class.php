<?php

class CtrlCommonFilePatcher extends CtrlCommonPatcher {
  
  protected function setPatcher() {
    $this->oPatcher = O::get('FilePatcher');
  }
  
}