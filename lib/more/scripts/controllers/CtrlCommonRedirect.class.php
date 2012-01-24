<?php

class CtrlCommonRedirect extends CtrlCommon {

  public function action_default() {
    RedirectRecord::save($this->params[2], $this->oReq->rq('url'));
    $this->redirect($this->oReq->rq('url'));
  }
  
}
