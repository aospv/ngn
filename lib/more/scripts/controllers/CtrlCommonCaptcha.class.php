<?php

class CtrlCommonCaptcha extends CtrlCommon {

  public function action_ajax_check() {
    if (isset($_SESSION['captcha_keystring']) and $_SESSION['captcha_keystring'] == $this->oReq->rq('keystring')) {
      $this->ajaxSuccess = true;
    } else {
      $_SESSION['captcha_keystring'] = 'wrong';
      $this->ajaxSuccess = false;
    }
  }

}
