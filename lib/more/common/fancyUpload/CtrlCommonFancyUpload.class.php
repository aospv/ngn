<?php

class CtrlCommonFancyUpload extends CtrlCommon {

  public function action_json_default() {
    // sleep(15); // debug
    O::get('FancyUploadTemp', array(
      'tempId' => $this->oReq->reqNotEmpty('tempId'),
      'multiple' => (bool)$this->oReq->rq('multiple')
    ))->upload($_FILES, $this->oReq->reqNotEmpty('fn'));
  }

}
