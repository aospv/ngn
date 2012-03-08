<?php

class CtrlAdminUsers extends CtrlAdmin {

  static $properties = array(
    'title' => 'Пользователи',
    'onMenu' => true
  );
  
  /**
   * @var UsersRegForm
   */
  protected $oURF;
  
  protected function initForm(array $data = array()) {
    $this->oURF = new UsersRegForm(array(
      'submitTitle' => 'Создать',
      'data' => $data,
      'active' => true
    ));
    if ($this->oURF->update()) {
      $this->redirect();
      return;
    }
    $this->d['form'] = $this->oURF->html();
  }

  public function action_new() {
    $this->initForm();
    $this->d['tpl'] = 'users/edit-account';
    $this->setPageTitle(LANG_USER_CREATING);
  }
  
  public function action_json_edit() {
    $this->json['title'] = 'Редактирование пользователя';
    $oF = new UsersEditFormAdmin($this->oReq->rq('id'));
    if ($oF->update()) return;
    return $oF;
  }
  
  public function action_edit() {
    $oF = new UsersEditFormAdmin($this->oReq->rq('id'));
    if ($oF->update()) {
      $this->redirect();
      return;
    }
    $this->d['form'] = $oF->html();
    $this->d['tpl'] = 'users/edit-account';
    $this->setPageTitle(LANG_USER_EDIT.': '.$this->d['user']['login']);
  }
  
  public function action_ajax_delete() {
    DbModelCore::delete('users', $this->oReq->r['id']);
  }
  
  public function action_create() {
    DbModelCore::create('users', array_merge($this->oReq->r, array('active' => 1)));
    $this->redirect();
  }
  
  public function action_ajax_activate() {
    DbModelCore::update('users', $this->oReq->r['id'], array('active' => 1));
    Ngn::fireEvent('users.activation', $this->oReq->r['id']);
  }
  
  public function action_ajax_deactivate() {
    DbModelCore::update('users', $this->oReq->r['id'], array('active' => 0));
  }

  public function action_default() {
    $this->d += DbModelCore::pagination(40, 'users');
    $this->setPageTitle('Общий список');
  }
  
  public function action_search() {
    $this->d['items'] = db()->select("
      SELECT id, login, active, email FROM users 
      WHERE login LIKE ? OR email LIKE ? LIMIT 10",
      $this->oReq->r['searchLogin'].'%', $this->oReq->r['searchLogin'].'%');
    $this->d['searchLogin'] = htmlentities(
      $this->oReq->r['searchLogin'], ENT_QUOTES, CHARSET);
    $this->setPageTitle(
      'Результаты поиска по фрагменту «'.$this->d['searchLogin'].'»');
  }

}