<?php

class CtrlPageVUserReg extends CtrlPage {

  // ====================== Default Inctance Data ==========================
  
  static public function getVirtualPage() {
    return array(
      'title' => 'Регистрация'
    );
  }
  
  // ============================= Instance ================================

  /**
   * Массив с данными авторизованого пользователя
   *
   * @var array
   */
  private $auth;
  
  /**
   * @var UsersRegistration
   */
  private $oUR;
  
  /**
   * Настройки
   *
   * @var array
   */
  private $conf;
  
  protected $subscribes;
  
  protected function init() {
    parent::init();
    $this->d['tpl'] = 'users/reg';
    /*
    $this->oUR = new UsersRegistration();
    if (!empty($this->settings['minPassLength']))
      $this->oUR->minPassLength = $this->settings['minPassLength'];
    $this->oUR->activation = empty($this->settings['activation']) ? false : true;
    */
    $this->conf = Config::getVar('userReg');
    $this->subscribes = db()->query('SELECT id, title FROM subs_list WHERE active=1 AND useUsers=1');
  }
  
  public function initParamActionN() {
    $this->paramActionN = 1;
  }  
  
  public function action_rules() {
    $this->setPageTitle('Правила регистрации');
    $this->d['tpl'] = 'users/rules';
  }
  
  public function action_default() {
    if (Auth::get('id')) {
      $this->error404('Ошибка', 'Вы авторизованы и не можете регистрироваться');
      return;
    }
    $this->d['tpl'] = 'users/reg';
    $this->setPageTitle('Регистрация');
    $oF = new UsersRegForm(array(
      'role' => isset($_GET['role']) ? $_GET['role'] : null,
      'active' => empty($this->settings['activation'])
    ));    
    $this->d['form'] = $oF->html();
    if ($oF->update()) {
      if (!empty($this->settings['authorizeAfterReg']))
        Auth::loginByPost($oF->data['login'], $oF->data['pass']);
      $this->redirect(Tt::getPath(1).'/complete');
    }
  }
  
  public function action_ajax_popupReg() {
    $oF = new UsersRegForm(array(
      'submitTitle' => 'Зарегистрироваться и войти',
      'active' => empty($this->settings['activation'])
    ));
    if ($oF->update()) {
      Auth::loginByPost($oF->elementsData['login'], $oF->elementsData['pass']);
      $this->ajaxSuccess = true;
    } else {
      $this->ajaxOutput = $oF->html().'<div class="clear"><!-- --></div>';
    }
  }
  
  /**
   * Страница с сообщением об успешной авторизации
   */
  public function action_complete() {
    $this->isDefaultAction = false;
    $this->d['tpl'] = 'users/regComplete';
  }
  
  /**
   * Страница с сообщением об успешной регистрации
   */
  public function action_welcome() {
    $this->isDefaultAction = false;
    $this->d['tpl'] = 'users/regWelcome';
  }
  
  public function action_activation() {
    if (!$this->settings['activation']) return;
    $this->d['tpl'] = 'users/activation';
    $this->d['success'] = UsersActivation::activate($this->oReq->r['code']);
    $this->redirect(Tt::getPath().'/welcome');
  }
  
  // ----------------------------------------------------
  
  protected function initSubmenu() {
    $items = array();
    if ($this->conf['allowLoginEdit']) {
      $items[] = array(
        'title' => 'Изменить логин',
        'link' => Tt::getPath(1).'/editLogin',
        'name' => 'editLogin'
      );
    }
    if ($this->conf['allowPassEdit']) {
      $items[] = array(
        'title' => 'Изменить пароль',
        'link' => Tt::getPath(1).'/editPass',
        'name' => 'editPass'
      );
    }
    if ($this->conf['allowEmailEdit']) {
      $items[] = array(
        'title' => 'Изменить ящик',
        'link' => Tt::getPath(1).'/editEmail',
        'name' => 'editEmail'
      );
    }
    if (Config::getVarVar('mysite', 'enable')) {
      if ($this->conf['allowNameEdit']) {
        $items[] = array(
          'title' => 'Изменить домен',
          'link' => Tt::getPath(1).'/editName',
          'name' => 'editName'
        );
      }
      if ($this->conf['allowMysiteThemeEdit']) {
        $items[] = array(
          'title' => 'Оформление Моего сайта',
          'link' => Tt::getPath(1).'/editMysiteTheme',
          'name' => 'editMysite'
        );
      }
    }
    if (Config::getVarVar('subscribe', 'onReg') and !empty($this->subscribes)) {
      $items[] = array(
        'title' => 'Подписка на рассылки',
        'link' => Tt::getPath(1).'/subscribe',
        'name' => 'subscribe'
      );
    }
    $this->d['submenu'] = getLinks($items, $this->action);
    foreach ($this->d['submenu'] as $v) {
      if ($v['name'] == $this->action) {
        $this->setPageTitle($v['title'], true);
        break;
      }
    }
  }
  
  /**
   * @var DbModelUsers
   */
  protected $user;
  
  protected function initUser() {
    $this->user = DbModelCore::get('users', Auth::get('id'));
    if (!$this->user) {
      $this->error404('Авторизуйтесь');
      return false;
    }
    return true;
  }
  
  public function action_editLogin() {
    if (empty($this->conf['allowLoginEdit']))
      throw new NgnException('Email change not allowed');
    $this->d['tpl'] = 'users/regEdit';
    $this->setPageTitle('Настройки пользователя');
    if (!$this->initUser()) return;
    $this->initSubmenu();
    $this->d['form'] = $this->processLoginEditForm()->html();
  }
  
  public function action_editPass() {
    if (empty($this->conf['allowPassEdit']))
      throw new NgnException('Password change not allowed');
    $this->d['tpl'] = 'users/regEdit';
    if (!$this->initUser()) return;
    $this->initSubmenu();
    $this->d['form'] = $this->processPassEditForm()->html();
  }
  
  public function action_editEmail() {
    if (empty($this->conf['allowEmailEdit']))
      throw new NgnException('Email change not allowed');
    $this->d['tpl'] = 'users/regEdit';
    if (!$this->initUser()) return;
    $this->initSubmenu();
    $this->d['form'] = $this->processEmailEditForm()->html();
  }  

  public function action_editName() {
    if (!Config::getVarVar('mysite', 'enable'))
      throw new NgnException('Mysite is disabled');
    if (empty($this->conf['allowNameEdit']))
      throw new NgnException('Name change not allowed');
    $this->d['tpl'] = 'users/regEdit';
    if (!$this->initUser()) return;
    $this->initSubmenu();
    $this->d['form'] = $this->processNameEditForm()->html();
  }
  
  public function action_editMysiteTheme() {
    if (!Config::getVarVar('mysite', 'enable'))
      throw new NgnException('Mysite is disabled');
    if (empty($this->conf['allowMysiteThemeEdit']))
      throw new NgnException('MysiteTheme change not allowed');
    $this->d['tpl'] = 'users/regEdit';
    if (!$this->initUser()) return;
    $this->initSubmenu();
    $this->processMysiteThemeForm();
  }
  
  protected function processFieldEditForm($fieldName, $fieldTitle) {
    $oF = new Form(new Fields(array(
      array(
        'name' => 'pass',
        'title' => 'Ваш пароль',
        'type' => 'password',
        'required' => true
      ),
      array(
        'name' => $fieldName,
        'title' => $fieldTitle,
        'required' => true
      )
    )));
    $oF->options['submitTitle'] = 'Изменить';
    $oF->setElementsData($this->user->getClean());
    if ($oF->isSubmittedAndValid()) {
      $data = $oF->getData();
      if (!$this->user->checkPass($data['pass']))
        $oF->globalError('Ваш пароль введён неверно');
      elseif (DbModelCore::get('users', $data[$fieldName], $fieldName))
        $oF->globalError("Такой $fieldTitle уже существует");
      else {
        DbModelCore::update('users', $this->userId, array($fieldName => $data[$fieldName]));
      }
    }
    return $oF;
  }
  
  protected function processLoginEditForm() {
    $oF = $this->processFieldEditForm('login', 'Логин');
    if ($oF->isSubmittedAndValid()) {
      $data = $oF->getData();
      Auth::loginByLogin($data['login']);
    }
    return $oF;
  }
  
  protected function processPassEditForm() {
    $oF = new Form(new Fields(array(
      array(
        'name' => 'curPass',
        'title' => 'Текущий пароль',
        'type' => 'password',
        'required' => true
      ),
      array(
        'name' => 'newPass',
        'title' => 'Новый пароль',
        'type' => 'password',
        'required' => true
      ),
    )));
    $oF->options['submitTitle'] = 'Изменить';
    $oF->setElementsData();
    if ($oF->isSubmittedAndValid()) {
      $data = $oF->getData();
      if (!$this->user->checkPass($data['curPass']))
        $oF->getElement('curPass')->error('Текущий пароль введён неверно');
      else
        DbModelCore::update('users', $this->user['id'], array('pass' => $data['newPass']));
    }
    return $oF;
  }
  
  protected function processEmailEditForm() {
    return $this->processFieldEditForm('email', 'e-mail');
  }
  
  protected function processNameEditForm() {
    return $this->processFieldEditForm('name', 'домен');
  }
  
  protected function processMysiteThemeForm() {
    $folder = UPLOAD_PATH.'/mysite/'.$this->user['id'];
    $file = $folder.'/bg.jpg';
    $this->d['fields'] = $fields = array(
      array(
        'name' => 'image',
        'title' => 'Картинка для шапки',
        'type' => 'image',
        'required' => true,
        'default' => file_exists($file) ?
          UPLOAD_DIR.'/mysite/'.$this->user['id'].'/bg.jpg' : ''
      ),
    );
    $oF = new Form(new Fields($fields));
    $oF->options['submitTitle'] = 'Изменить';
    $data = $oF->setElementsData();
    if ($oF->isSubmittedAndValid()) {
      Dir::make($folder);
      copy($data['image']['tmp_name'], $file);
      unlink($data['image']['tmp_name']);
    }
    $this->d['form'] = $oF->html();
  }
  
  public function action_deleteFile() {
    if (!Config::getVarVar('mysite', 'enable'))
      throw new NgnException('Mysite is disabled');
    if (empty($this->conf['allowMysiteThemeEdit']))
      throw new NgnException('MysiteTheme change not allowed');
    if (!$this->initUser()) return;
    if (file_exists(UPLOAD_PATH.'/mysite/'.$this->user['id'].'/bg.jpg'))
      unlink(UPLOAD_PATH.'/mysite/'.$this->user['id'].'/bg.jpg');
    $this->redirect(Tt::getPath(1).'/editMysiteTheme');
  }
  
  public function action_updateUserDataPageId() {
    db()->query("UPDATE users SET userDataPageId=?d WHERE id=?d",
      $this->oReq->r['userDataPageId'], Auth::get('id'));
    $this->redirect();
  }
  
  public function action_lostpass() {
    $this->setPageTitle('Забыли пароль?');
    $oF = new Form(new Fields(array(
      array(
        'name' => 'email',
        'title' => 'E-mail',
        'type' => 'email',
        'required' => true
      )
    )));
    $oF->options['submitTitle'] = 'Выслать';
    $data = $oF->setElementsData($_POST);
    $this->d['form'] = $oF->html();
    if ($oF->isSubmittedAndValid()) {
      $this->redirect(Tt::getPath(1).
        (UsersCore::sendLostPass($data['email']) ? '/lostpassComplete' : '/lostpassFailed'));
    }
    $this->d['tpl'] = 'users/lostpass';    
  }
  
  public function action_lostpassFailed() {
    print 'Ошибка отправки';
    $this->hasOutput = false;
  }
  
  public function action_lostpassComplete() {
    print 'Отправлено успешно';
    $this->hasOutput = false;
  }
  
  public function action_subscribe() {
    if (!Config::getVarVar('subscribe', 'onReg') or empty($this->subscribes))
      throw new NgnException('Action not allowed');
    $this->initSubmenu();
    $this->processSubscribeForm();
    $this->d['tpl'] = 'users/regEdit';
  }
  
  protected function processSubscribeForm() {
    foreach ($this->subscribes as $v) {
      $fields[] = array(
        'name' => 'subsList['.$v['id'].']',
        'title' => $v['title'],
        'type' => 'bool'
      );
    }
    $cur = array();
    foreach (db()->selectCol('
    SELECT listId FROM subs_users WHERE userId=?d',
    $this->userId) as $listId)
      $cur['subsList'][$listId] = 1;
    
    $oF = new Form(new Fields($fields));
    $data = $oF->setElementsData($cur);
    if ($oF->isSubmittedAndValid()) {
      $this->d['saved'] = true;
      foreach ($data['subsList'] as $listId => $subscribed) {
        if ($subscribed) {
          db()->query('REPLACE INTO subs_users SET userId=?d, listId=?d',
            $this->userId, $listId);
        } else {
          db()->query('DELETE FROM subs_users WHERE userId=?d AND listId=?d',
            $this->userId, $listId);
        }
      }
    }
    $this->d['form'] = $oF->html();
  }
  
}
