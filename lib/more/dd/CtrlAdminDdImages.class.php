<?php

class CtrlAdminDdImages extends CtrlAdminPagesBase {

  /**
   * @param string sm/md
   */
  protected function resizeImages($type) {
    Arr::checkEmpty($this->page, array('strName', 'id'));
    $o = new PartialJobDdImages($this->page['strName'], $this->page['id'],
      $this->oReq->rq('w'), $this->oReq->rq('h'), $type);
    $this->json = $o->makeStep($this->oReq->rq('step'));
  }
  
  public function action_json_resizeSmImages() {
    $this->resizeImages('sm');
  }
  
  public function action_json_resizeMdImages() {
    $this->resizeImages('md');
  }

}
