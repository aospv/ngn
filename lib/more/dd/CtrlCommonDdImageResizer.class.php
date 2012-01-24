<?php

class CtrlCommonDdImageResizer extends CtrlCommon {

  public function action_json_asd() {
    $r =  new Form(new Fields(array(array(
      'title' => 'asdqwdqwdqw'
    ))));
    if ($r->isSubmitted()) {
      $this->json['dialog'] = array(
        'cls' => 'Ngn.Dialog.Loader.Simple',
        'options' => array(
          'title' => 'suck dick'
        )
      );
      return;
    }
    return $r;
  }
  
  public function action_json_default() {
  }

}
