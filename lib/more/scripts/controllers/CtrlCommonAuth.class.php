<?php

class CtrlCommonAuth extends CtrlCommon {
  
  protected function init() {
    $this->d['mainTpl'] = 'ajax';
  }

  public function action_default() {
    $this->d['tpl'] = 'common/auth-ajax';
  }
  
  public function action_ajax_popup() {
    $oF = new AuthForm();
    $oF->setAction(Tt::getPath(0).'/c/auth/ajax_popup');
    if ($oF->isSubmittedAndValid()) {
      $this->ajaxSuccess = true;
      return;
    }
    print $oF->html().'<div class="clear"><!-- --></div>';
  }
  
  public function action_ajax_top() {
    $this->ajaxOutput = Tt::getTpl('top', array('path' => $this->oReq->rq('path')));
  }
  
}
