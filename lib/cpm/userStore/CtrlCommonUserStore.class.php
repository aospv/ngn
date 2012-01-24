<?php

class CtrlCommonUserStore extends CtrlCommon {

  public function action_json_settings() {
    if (!UserStoreCore::allowed(Auth::get('id'))) throw new AccessDenied(); 
    return $this->actionJsonFormUpdate(new UserStoreSettingsForm(Auth::get('id')));
  }
  
  public function action_ajax_rules() {
    $this->ajaxOutput =
      DbModelCore::get('userStoreSettings', $this->getParam(3))->r['settings']['rules'];
  }

}