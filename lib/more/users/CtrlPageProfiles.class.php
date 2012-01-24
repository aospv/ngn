<?php

class CtrlPageProfiles extends CtrlPageDdItems {

  /**
   * @var DbModelPages
   */
  protected $myProfilePage;

  /*
  protected function initSettings() {
    parent::initSettings();
    $this->myProfilePage = DbModelCore::get('pages', $this->page['settings']['myProfileId']);
    //$this->page['strName'] = $this->myProfilePage['strName'];
    $this->page->r['strName'] = $this->strName = $this->settings['strName'] = $this->myProfilePage['strName'];
  }
  */
  
  protected function setFilterRole($v) {
    $this->oManager->oItems->cond->addFilter(array(
      'table' => 'users',
      'key' => 'role',
      'value' => explode('+', $v)
    ));
    if (($title = Arr::get_value(UsersCore::getRoles(), 'name', $v, 'title')) !== false)
      $this->setPageTitle($title);
  }
  
  public function action_list() {
    $this->d['editPath'] = $this->myProfilePage['path'];
    parent::action_list();
  }

}