<?php

class PartialJobVkMsgs extends PartialJob {

  public $jobsInStep = 1;

  protected function initJobs() {
    $this->jobs = O::get('VkFriends', VkSite::getAuth())->getFriends();
  }
  
  public function getLastStep() {
    $index = array_search(VkSite::msgs()->getLastSentUserId(), $this->jobs);
    return $index * $this->jobsInStep;
  }
  
  protected function makeJob($n) {
    O::get('VkMsgs', VkSite::getAuth())->send(array(
      'to_id' => $this->jobs[$n],
      'message' => $this->jobsData['message']
    ));
  }
  
}