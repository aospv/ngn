<?php

/**
 * Ичпользуется для редактирования своего профиля
 */
class CtrlPageMyProfile extends CtrlPageDdStatic {

  protected $profileUserId;
	
  protected function init() {
    $this->profileUserId = $this->getParam(1);
    Misc::checkEmpty($this->profileUserId);
    $this->completeRedirectType = 'edit';
    $this->d['userImage'] = UsersCore::getImageData(Auth::get('id'));
    parent::init();
  }
  
  protected function _initPriv() {
    parent::_initPriv();
    $this->priv['create'] = $this->priv['edit'] = $this->profileUserId == Auth::get('id');
  }
  
  protected function getStaticId() {
    return $this->profileUserId * $this->page['id'];
  }
  
  public function action_showItem() {
    $this->error404();
  }

}
