<?php

class CtrlCommonAuth extends CtrlCommon {
  
  protected function init() {
    $this->d['mainTpl'] = 'ajax';
  }

  public function action_default() {
    $this->d['tpl'] = 'common/auth-ajax';
  }
  
  public function action_json_popup() {
    $oF = new AuthForm();
    $oF->setAction('/c/auth/json_popup');
    if ($oF->isSubmittedAndValid()) {
      $this->json['success'] = true;
      return;
    }
    $this->jsonFormAction($oF);
  }
  
  public function action_ajax_top() {
    $this->ajaxOutput = Tt::getTpl('top', array('path' => $this->oReq->rq('path')));
  }
  
}
