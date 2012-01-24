<?php

class GrabberPartialJobImporter extends GrabberPartialJob {

  /**
   * @var GrabberDdImporter
   */
  protected $oGI;
  
  protected function init() {
    $this->oGI = new GrabberDdImporter($this->oG);
  }

  protected function initJobs() {
    $this->jobsTotal = count($this->oG->getSavedListPageItems());
  }
  
  protected function makeJob($n) {
  }
  
  public function makeStep($step) {
    if (!($this->oGI->importPageListItem($step))) {
      return;
    }
    Settings::set('giCount'.$this->channelId, ($step+1)*$this->jobsInStep);
  }
  
}
