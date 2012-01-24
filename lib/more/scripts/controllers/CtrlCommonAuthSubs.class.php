<?php

class CtrlCommonAuthSubs extends CtrlCommonAuth {
  
  function action_default() {
    $this->d['tpl'] = 'common/auth-subs-ajax';
  }  
  
}
