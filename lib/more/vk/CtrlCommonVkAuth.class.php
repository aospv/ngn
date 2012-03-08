<?php

class CtrlCommonVkAuth extends CtrlCommon {

  protected function init() {
    Misc::checkEmpty(Config::getVarVar('userReg', 'vkAuthEnable'));
  }

  public function action_ajax_exists() {
    $this->ajaxSuccess = DbModelCore::get('users', $this->oReq->rq('login'), 'login');
  }
  
  protected function checkHash() {
    if (md5(
      Config::getVarVar('vk', 'appId').
      $this->oReq->rq('uid').
      Config::getVarVar('vk', 'secKey')
    ) != $this->oReq->rq('hash')) throw new NgnException('Hash error');
  }
  
  public function action_ajax_reg() {
    $d = $this->oReq->p;
    $d['active'] = 1;
    $this->checkHash();
    $imageUrl = $d['image'];
    unset($d['image']);
    $userId = DbModelCore::create('users', $d, true);
    Auth::loginByLogin($this->oReq->p['login']);
    if (($page = DbModelCore::get('pages', 'myProfile', 'controller')) !== false) {
      $oIM = DdCore::getItemsManager($page['id'], array(
        'staticId' => $userId * $page['id']
      ));
      if (isset($oIM->oForm->oFields->fields['image'])) {
        $tempFile = TEMP_PATH.'/'.Misc::randString(10);
        O::get('Curl')->copy($imageUrl, $tempFile);
        $oIM->create(array(
          'image' => array(
            'tmp_name' => $tempFile
           )
        ));
      }
    }
    $this->ajaxSuccess = true;
  }
  
  public function action_ajax_default() {
    $this->checkHash();
    $this->ajaxSuccess = Auth::loginByLogin($this->oReq->rq('login'));
  }

}