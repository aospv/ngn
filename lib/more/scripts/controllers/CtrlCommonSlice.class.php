<?php

class CtrlCommonSlice extends CtrlCommon {
  
  protected function init() {
    if (!Misc::isAdmin()) throw new NgnException('You are not admin');
    $this->hasOutput = false;
  }
  
  public function action_ajax_save() {
    DbModelCore::replace('slices', $this->oReq->r['id'], $this->oReq->r, true);
    print DbModelCore::get('slices', $this->oReq->r['id'])->r['text'];
  }
  
  public function action_ajax_savePos() {
    Slice::savePos($this->oReq->rq('id'), array(
      'x' => (int)$this->oReq->rq('x').'px',
      'y' => (int)$this->oReq->rq('y').'px'
    ));
    $this->ajaxSuccess = true;
  }
  
}