<?php

class CtrlCommonPageBlock extends CtrlCommon {

  public function action_ajax_get() {
    $r = PageBlockCore::getBlockHtmlData(
      DbModelCore::get('pageBlocks', $this->getParam(3))
    );
    $this->ajaxOutput = $r['html'];//newarr;
  }

  public function action_ajax_get2() {
    $r = PageBlockCore::getStaticBlockHtmlData(
      $this->getParam(3),
      $this->getParam(4)
    );
    $this->ajaxOutput = $r['html'];//newarr;
  }
  
}
