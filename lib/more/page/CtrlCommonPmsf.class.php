<?php

/**
 * Page module static files
 */
class CtrlCommonPmsf extends CtrlCommon {

  public function action_default() {
    $ext = $this->getParam(3);
    header('Content-type: text/'.(Misc::hasSuffix('js', $ext) ? 'javascript' : 'css'));
    $this->hasOutput = false;
    include CORE_PAGE_MODULES_PATH.'/'.$this->getParam(2).'/'.$ext.'.php';
  }

}
