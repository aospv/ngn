<?php

class CtrlPageUserData extends CtrlPage {
  
  protected $defaultAction = 'default';
  
  public $requiredSettings = array('profiles');
  
  /**
   * Страница полдьзователя
   * 
   * @var bool
   */
  public $isUserPage;
  
  protected $dataUserId;
  
  /**
   * Флаг означает, что страница принадлежит секущему авторизованному пользователю
   *
   * @var bool
   */
  public $isPersonal = false;
  
  private $personalActions = array('answers');
  
  protected $allowEmailSend = false;
  
  public function initParamActionN() {
    $this->paramActionN = 2;
  }
  
  public function init() {
    $this->dataUserId = $this->getParam(1);
    $this->d['user'] = DbModelCore::get('users', $this->dataUserId);
    if (!$this->d['user']) {
      $this->error404();
      return;
    }
    $this->d['isPersonal'] = $this->isPersonal = (Auth::get('id') == $this->dataUserId);
    $this->initProfiles();
    //$this->initComments();
    //$this->initUserItems();
    //$this->initLevel();
    
    // Перед инициализацией класса CtrlPage, в котором инициализируется объект
    // комментариев, для этого объекта нужно определить $this->id2
    $this->id2 = $this->dataUserId;

    if (!empty($this->settings['allowEmail'])) {
      if (!Auth::get('id')) {
        if (!empty($this->settings['allowAnonimEmail']))
          $this->allowEmailSend = true;
      } else {
        $this->allowEmailSend = true;
      }
    }
    $this->d['allowEmailSend'] = $this->allowEmailSend;
    $this->d['submenu'] = UserMenu::get(
      $this->d['user'],
      $this->d['page']['id'],
      $this->action
    );
    parent::init();
    
    // А для инфы пользователя вообще никаких привилегий не нужно, т.к.
    // редактировать её может только один человек
    //unset($this->d['priv']['edit']);
    //unset($this->d['priv']['create']);
    //if ($this->dataUserId = Auth::get('id')) $this->d['priv']['edit'] = 1;
  }
  
  protected function initMsgsCountData() {
    $this->d['msgsCount'] = db()->selectCell(
      'SELECT COUNT(*) FROM comments WHERE userId=?d', $this->dataUserId);
  }
  
  /**
   * Определяет привилегию комментировать
   */
  public function initSubPriv() {
    $privProfile = isset($this->d['profiles'.'.data']['priv']) ?
      $this->d['profiles'.'.data']['priv'] : null;
    if (!$privProfile) {
      // Если настройки не определены, выставляем все разрешения позитивными
      $this->setPriv('sub_create', true);
      $this->setPriv('sub_view', true);
    } else {
      $this->setPriv('sub_create',
        $privProfile['profile_sub_create']['k'] == 'nobody' ? false : true);
      $this->setPriv('sub_view',
        $privProfile['profile_sub_view']['k'] == 'nobody' ? false : true);
    }
  }
  
  /**
   * Определяет данные для всех существующих профилей пользователя
   *
   */
  protected function initProfiles() {
    if (empty($this->settings['profilePageIds'])) return;
    foreach ($this->settings['profilePageIds'] as $pageId) {
      $page = DbModelCore::get('pages', $pageId);
      $this->d['profiles'][] = array(
        'page' => $page,
        'data' => O::get('DdItems', $page['id'])->
          getItemByField('userId', $this->dataUserId),
        'fields' => O::get('DdFields', $page['strName'])->getFieldsF()
      );
    }
  }
    
  protected function initUserItems() {
    /* @var $oPages Pages */
    $oPages = O::get('Pages');
    if (isset($this->settings['userItems'])) {
      foreach ($this->settings['userItems'] as $v) {
        $oItems = new DdItems($v['pageId']);
        $oItems->cond->setLimit(empty($this->settigns['userItemsLimit']) ? 20 : $this->settigns['userItemsLimit']);
        $oItems->cond->addF('userId', $this->dataUserId);
        $oItems->cond->setOrder('dateCreate DESC');
        $this->d['userItems'][$v['pageId']]['page'] = DbModelCore::get('pages', $v['pageId']);
        $this->d['userItems'][$v['pageId']]['items'] = $oItems->getItems();
        $this->d['userItems'][$v['pageId']]['fields'] =
          O::get('DdFields', $v['strName'], $v['pageId'])->getFieldsF();
      }
    }
  }
  
  protected function initLevel() {
    if (!Config::getVarVar('level', 'on', true)) return;
    $this->d['level'] = db()->selectCell(
      'SELECT level FROM level_users WHERE userId=?d', $this->dataUserId);
  }

  protected function initPathAndTitle() {
    $this->setPathDataLast($this->d['user']['login']);
    if (empty($this->d['user']['login']))
      throw new NgnException('User ID='.$this->d['user']['id'].' has empty login');
    $this->setHeadTitle($this->d['user']['login']);
  }
  
  /*
  protected function allowAction($action) {
    // Если это страница не текущего авторизованого пользователя и если 
    // экшн является Персоняльным, то мы запрещаем его выполнение
    if (!$this->isPersonal and 
         in_array($action, $this->personalActions)) return false;
    return parent::allowAction($action);
  }
  */
  
  public function action_default() {
    // Страница пользователя
    $this->d['tpl'] = 'userData/default';
    $this->initMsgsCountData();
    $this->initPathAndTitle();
    $this->initLevel();
    /*
    // Комменты
    $oSub_comments = $this->oSub;
    if ((get_class($oSub_comments) == 'Sub_comments')) {
      $oSub_comments->action_default();
    }
    */
    $this->d['answers'] = false; // Comments::getAnswers($ud['id'], 20);
  }
  
  /*
  function action_search() {
    $this->setPageTitle('Поиск анкет');
    $this->resetPathData(Tt::getPath(), 'Поиск анкет');
    
    $oF = new Form(new Fields(array(
      array(
        'name' => 'birthDate_from',
        'title' => 'Возраст от',
        'type' => 'num',
        'maxlength' => 2
      ),
      array(
        'name' => 'birthDate_to',
        'title' => 'Возраст до',
        'type' => 'num',
        'maxlength' => 2
      ),
      array(
        'name' => 'isPhoto',
        'title' => 'с фотографией',
        'type' => 'bool'
      ),
    )));
    $oF->submitTitle = 'Искать';
    $oF->setElementsData($_POST);
    $this->d['form'] = $oF->html();
    $this->d['tpl'] = $this->getTpl('form');
    
    $strName = $this->settings['profiles'][0]['strName'];
    $oFld = new DdFields($strName, $this->page['id']);


    if ($_POST) {
      $this->d['fields'] = $oFld->getFields();
      $from = $_POST['birthDate_from'];
      $to = $_POST['birthDate_to'];
      $from = date('Y-m-d', time()-(365.25*24*60*60*$from));
      $to = date('Y-m-d', time()-(365.25*24*60*60*$to));
      //if ($to and $from)
        //$oI->addRangeFilter('birth_date', $to, $from);
      $oI->addNullFilter('photo', !(bool)$_POST['isPhoto']);
      $this->d['items'] = $oI->getItems();
      $this->d['tpl'] = $this->getTpl('default');
    }        
  }
  
  function action_answers() {
    $this->d['tpl'] = 'userData/answers';
    $this->setPageTitle('Ответы');
    $this->d['items'] = Comments::getAnswers(Auth::get('id'));
  }
  
  ////
  
  function action_json_getTags() {
    if (!$pageId = $this->params[2])
      throw new NgnException('$pageId not defined');
    if (!$tagType = $this->params[3])
      $this->error404('Тип тэга не задан');
    $i = 0;
    foreach (DdTags::getTags($tagType, $pageId) as $v) {
      $this->json[] = array($v['tagId'], $v['title'], null, $v['title']);
      $i++;
    }
  }
  
  function action_tag() {
    if (!$pageName = $this->params[2]) {
      $this->error404('Страница не задана');
      return;
    }
    if (!$tagType = $this->params[3]) {
      $this->error404('Тип тэга не задан');
      return;
    }
    if (!$tagName = $this->params[4]) {
      $this->error404('Тэг не задан');
      return;
    }
    
    $this->setProfiles();
    
    $profilePageId = Arr::get_value(
      $this->d['profiles']['page'],
      'name', $pageName, 'id');
      
    $userIds = db()->selectCol(
      'SELECT id FROM tags_items WHERE type=? AND id=?d AND name=?',
      $tagType, $profilePageId, $tagName);
    
    $tag = DdTags::getTag($tagName, $tagType, $profilePageId);
    
    $title = 'Выборка пользователей по тэгу «'.$tag['title'].'»';
    $this->setPageTitle($title);
    $this->resetPathData(Tt::getPath(), $title);
    
    $users = db()->select('SELECT * FROM users WHERE id IN (?d)', $userIds);
    UsersCore::extendImageData($users);
    $this->d['items'] =& $users;
    $this->d['tpl'] = 'userData/userList'; 
  }
  */
  
  public function action_email() {
    if (!$this->allowEmailSend)
      throw new NgnException('Email masseages from anonim users are not allowed');
    $this->initPathAndTitle();
    $this->setPageTitle('Отправка e-mail пользователю '.$this->d['user']['login']);
    $this->setPathData(Tt::getPath(), 'Отправка e-mail');
  }
  
  public function action_emailSend() {
    if (!$this->allowEmailSend)
      throw new NgnException('Email masseages from anonim users are not allowed');
  }
  
  public function action_comments() {
    if (!$this->settings['commentsEnable'])
      throw new NgnException('Comments desibled');
    $this->setPageTitle('Комментарии');
    $this->setPathData(Tt::getPath(), 'Комментарии');
    Comments::addUserFilter($this->dataUserId);
    $this->d['items'] = Comments::getLast(20);
    foreach ($this->d['items'] as &$v) {
      $v['showPage'] = true;
      $v += UsersCore::getImageData($v['userId']);
    }
    $this->d['tpl'] = 'comments/default';
  }
  
  public function action_answers() {
    if (!$this->settings['answersEnable'])
      throw new NgnException('Answers desibled');
    $this->setPageTitle('Ответы');
    $this->setPathData(Tt::getPath(), 'Ответы');
    $this->d['items'] = Comments::getAnswers($this->dataUserId, 20);
    foreach ($this->d['items'] as &$v) {
      $v['showPage'] = true;
      $v += UsersCore::getImageData($v['userId']);
    }
    $this->d['tpl'] = 'comments/default';
  }

}