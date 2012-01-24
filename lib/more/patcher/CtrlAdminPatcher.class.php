<?php

class CtrlAdminPatcher extends CtrlAdmin {

  static $properties = array(
    'title' => 'Патчи',
    'onMenu' => true
  );
  
  function init() {
    $this->d['patches'] = O::get('DbPatcher')->getPatches();
    $this->d['tpl'] = 'patcher/default';
  }
  
  function action_make() {
    $this->d['result'] = O::get('DbPatcher')->make($this->oReq->r['patchN']);
    $this->d['tpl'] = 'patcher/make';
  }
  
  function action_patch() {
    O::get('DbPatcher')->patch();
    $this->redirect(Tt::getPath(2));
  }

}
