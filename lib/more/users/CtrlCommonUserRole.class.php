<?php

class CtrlCommonUserRole extends CtrlCommon {

  public function action_json_default() {
    return $this->actionJsonFormUpdate(new UserRoleForm(Auth::get('id')));
  }

}
