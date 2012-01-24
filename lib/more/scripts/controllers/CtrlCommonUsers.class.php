<?php

class CtrlCommonUsers extends CtrlCommon {
  
  function action_json_sendLostPass() {
    $this->json['success'] = UsersCore::sendLostPass($this->oReq->r['email']);
  }
  
}
