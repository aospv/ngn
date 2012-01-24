<?php

class CtrlAdminPagePrivilegesOld extends CtrlAdminPagesBase {

  static $properties = array(
    'title' => 'Привилегии',
    'descr' => 'Привилегии пользователей',
    'onMenu' => true,
    'order' => 40
  );
  
  protected $allowSlavePage = false;
  
  /**
   * @var PrivilegesManager
   */
  public $oPrivileges;

  /**
   * @var DdPrivilegesManager
   */
  public $oDDPrivileges;

  protected function init() {
    parent::init();
    $this->oPrivileges = new PrivilegesManager();
    $this->oDDPrivileges = new DdPrivilegesManager();
  }

  public function action_default() {
    $this->d['tpl'] = 'privileges/default';
    $this->d['items'] = $this->oPrivileges->getAll();
  }

  public function action_new() {
    $this->isDefaultAction = false;
    $this->d['types'] = $this->oPrivileges->getTypes();
    $this->d['tpl'] = 'privileges/add';
  }

  public function action_create() {
    $this->oPrivileges->addPrivs($this->oReq->r['userId'], $this->oReq->r['pageId'], $this->oReq->r['types']);
    $this->redirect(Tt::getPath(2));
  }

  public function action_delete() {
    $this->oPrivileges->deleteByPage($this->pageId);
    $this->redirect(Tt::getPath(2));
  }

  public function action_updateByUser() {
    $this->oPrivileges->addPrivsByUser($this->oReq->r['userId'],
      isset($this->oReq->r['priv']) ? $this->oReq->r['priv'] : null);
    $this->redirect('referer');
  }

  public function action_updateByPage() {
    $this->oPrivileges->addPrivsByPage($this->pageId, $this->oReq->r['priv']);
    $this->redirect('referer');
  }

  public function action_updateDD() {
    if (! $this->oReq->r['userId'])
      throw new NgnException("\$this->oReq->r['userId'] not defined");
    $this->oDDPrivileges->addPrivs_array($this->oReq->r['userId'], $_POST['privs']);
    $this->redirect('referer');
  }

  public function action_userPrivileges() {
    if (! $this->oReq->r['userId'])
      throw new NgnException('$this->oReq->r[\'userId\'] not defined');
    $this->d['tpl'] = 'privileges/userPrivileges';
    $this->d['user'] = DbModelCore::get('users', $this->oReq->r['userId']);
    $this->d['ddPrivs'] = $this->oDDPrivileges->getByUser($this->oReq->r['userId']);
    $this->d['ddFields'] = $this->oDDPrivileges->getFields();
    $this->d['ddActions'] = $this->oDDPrivileges->getActions();
    $this->d['types'] = $this->oPrivileges->getTypes();
    $this->d['privs'] = $this->oPrivileges->getByUser($this->oReq->r['userId']);
    $this->setPageTitle(
      'Привилегии для пользователя <a href="' . TplAdmin::getUserPath(
        $this->oReq->r['userId']) . '">' . $this->d['user']['login'] . '</a>');
  }

  public function action_pagePrivileges() {
    $this->d['tpl'] = 'privileges/pagePrivileges';
    $this->d['types'] = $this->oPrivileges->getTypes();
    $this->d['privs'] = $this->oPrivileges->getByPage($this->pageId);
    $this->setPageTitle('Привилегии для раздела «' . $this->d['page']['title'] . '»');
  }

  public function action_cleanup() {
    $this->oPrivileges->cleanup();
    $this->redirect();
  }

  public function action_lockPage() {
    if (!$this->pageId)
      throw new NgnException('$this->pageId not defined');
    Pages::updateS($this->pageId, array(
      'isLock' => 1
    ));
    $this->redirect('referer');
  }

  public function action_unlockPage() {
    if (! $this->pageId)
      throw new NgnException('$this->pageId not defined');
    Pages::updateS($this->pageId, array(
      'isLock' => 0
    ));
    $this->redirect('referer');
  }

}