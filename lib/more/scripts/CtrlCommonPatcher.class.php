<?php

abstract class CtrlCommonPatcher extends CtrlCommon {
  
  protected $oPatcher;

  public function init() {
    $this->setPatcher();
    $this->d['oPatcher'] = $this->oPatcher;
    $this->d['mainTpl'] = 'installer/patcher';
  }
  
  abstract protected function setPatcher();

  protected function action_patch() {
    $this->hasOutput = false;
    set_time_limit_q(0);
    $this->oPatcher->setLogger('prr');
    if (isset($this->oReq->r['n'])) {
      $this->oPatcher->make($this->oReq->r['n']);
    } else
      $this->oPatcher->patch();
    print '<p><a href="/">'.SITE_TITLE.'</a></p>';
  }
  
}
