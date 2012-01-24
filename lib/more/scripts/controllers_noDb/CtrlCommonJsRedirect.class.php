<?php

class CtrlCommonJsRedirect extends CtrlBlank {

  protected function init() {
    $this->d['mainTpl'] = 'admin/main';
  }
  
  public function action_default() {
    $this->d['redirect'] = $this->oReq->rq('r');
    $this->d['tpl'] = 'common/jsRedirect';
  }
  
}
