<?php

class CtrlPagePhotoalbum extends CtrlPageDdItemsMaster {
  
  public function action_showItem() {
    $this->redirect('/'.$this->settings['slavePageId'].'/v.'.DdCore::masterFieldName.
      '.'.$this->params[1]);
  }

}
