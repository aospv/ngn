<?php

class CtrlCommonUpdater extends CtrlCommon {
  
  public $paramActionN = 2;

  // -------- remote
  
  function action_ajax_checkForNewBuild() {
    $this->hasOutput = false;
    $o = new NgnUpdater();
    print $o->getNewBuildN();
  }
  
  function action_ajax_update() {
    $this->ajaxSuccess = true;
    $o = new NgnUpdater();
    $o->update();
  }
  
  function action_update() {
    $this->hasOutput = false;
    $o = new NgnUpdater();
    $o->update();
  }
  
  // ------- local -------- access from NgnUpdater
  
  public function action_ajax_getBuildN() {
    print BUILD;
  }
  
  public function action_downloadNgn() {
    $this->redirect('/'.Tt::getPath(0).UPLOAD_DIR.'/temp/ngn.zip');
  }
  
}
