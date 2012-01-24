<?php

class CtrlPageVPageBlock extends CtrlPageVUserGroup {

  static public function getVirtualPage() {
    return array(
      'title' => 'dqqwdwqd'
    );
  }

  public function action_ajax_get() {
    $r = PageBlockCore::getBlockHtmlData(
      DbModelCore::get('pageBlocks', $this->getParam(2)),
      $this
    );
    $this->ajaxOutput = $r['html'];//newarr;
  }

  public function action_ajax_get2() {
    $r = PageBlockCore::getStaticBlockHtmlData(
      $this->getParam(2),
      $this->getParam(3),
      $this
    );
    $this->ajaxOutput = $r['html'];//newarr;
  }
  
}
