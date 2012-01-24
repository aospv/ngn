<?php

class CtrlPageAuth extends CtrlCommon {

  public function init() {
    $this->d['mainTpl'] = 'no-auth/main';
  }
  
  public function action_ajax_invites() {
    Tt::tpl('no-auth/invadors');
  }

}
