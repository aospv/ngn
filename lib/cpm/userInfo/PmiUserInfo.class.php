<?php

class PmiUserInfo extends Pmi {
  
  public $title = 'Информация пользователя';
  public $controller = 'userData';
  public $oid = 150;
  public $onMenu = false;
  protected $profilePageId;
  
  protected function installProfilePageModule($uiNode) {
    $node = array('title' => $uiNode['title']);
    if (isset($uiNode['parentId'])) $node['parentId'] = $uiNode['parentId'];
    $this->profilePageId = Pmi::get('profileSimple')->install($node);
  }
  
  public function install($node) {
    $this->installProfilePageModule($node);
    parent::install($node);
  }
  
  protected function getSettings() {
    return array(
      'profiles' => array($this->profilePageId)
    );
  }
  
}
