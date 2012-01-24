<?php

class CtrlCommonVk extends CtrlCommon {

  public function action_asd() {
    $this->hasOutput = false;
    print '<table><tr><td valign="top">';
    prr(VkSite::friends()->getFriends());
    print '</td><td valign="top">';
    prr(VkSite::msgs()->getSentUserIds());
    print '</td></tr></table>';
    //$this->json['friends'] = VkSite::friends()->getFriends();
    //$this->json['sentUsers'] = VkSite::msgs()->getSentUserIds();
  }
  
  public function action_json_info() {
    $this->json['friends'] = VkSite::friends()->getFriends();
    $this->json['sentUsers'] = VkSite::msgs()->getSentUserIds();
    if (($id = VkSite::msgs()->getLastSentUserId()) !== false) {
      $this->json['lastSentUser'] = array(
        'id' => $id,
        'name' => VkSite::userInfo()->getName($id)
      );
    }
  }
  
  public function action_json_getLastStep() {
    $this->json = O::get('PartialJobVkMsgs')->getLastStep();
  }
  
  public function action_json_pjSend() {
    $this->json = O::get('PartialJobVkMsgs')->setJobsData(array(
      'message' => $this->oReq->rq('message')
    ))->makeStep($this->oReq->rq('step'));
  }

}
