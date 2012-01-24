<?php

class CtrlCommonPageTree extends CtrlCommon {

  protected $defaultAction = 'json_getTree';

  public function action_json_getTree() {
    $this->json = O::get('MifTreePages')->getTree();
  }
  
}
