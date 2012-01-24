<?php

class CtrlCommonCc extends CtrlCommon {

  public function action_ajax_sf() {
    SFLM::clearJsCssCache();
  }

}
