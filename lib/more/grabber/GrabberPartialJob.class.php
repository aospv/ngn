<?php

abstract class GrabberPartialJob extends PartialJob {

  protected $channelId;
  
  /**
   * @var GrabberSourceAbstract
   */
  protected $oG;
  
  public function __construct($channelId) {
    $this->jobsInStep = 1;
    $this->channelId = $channelId;
    $this->oG = Grabber::getSource($channelId);
    $this->oG->itemsLimit = 99999;
    $this->init();
    parent::__construct();
  }
  
  protected function init() {}
  
  public function getId() {
    return parent::getId().'-'.$this->channelId;
  }
  
}
