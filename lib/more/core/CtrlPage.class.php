<?php

/**
 * Особенностью этого типа контроллеров является то, что выполнение dispatch()
 * не возможно пока не быдет успешно выполнен метод setPage()
 */
abstract class CtrlPage extends CtrlCommon {
  
  /**
   * Массив с данными текущего раздела
   *
   * @var   DbModelPages
   */
  public $page;
  
  /**
   * Массив с настройками текущего раздела
   *
   * @var   array
   */
  public $settings;
  
  /**
   * Поля настроект текущего раздела, которые обязательно должны быть определены
   *
   * @var   array
   */
  public $requiredSettings = array();
  
  protected $defaultSettings = array();
  
  /**
   * Пака с шаблонами. По умолчанию - 'dd'
   *
   * @var string
   */
  public $tplFolder = 'dd';
  
  /**
   * Идентификатор необходим для инициализации объекта комментов
   *
   * @var integer
   */
  protected $id2;
  
  /**
   * ID текущего авторизованого пользователя
   * 
   * @var integer
   */
  public $userId;
  
  /**
   * Свойства клавного шаблона
   *
   * @var array
   */
  protected $mainTplProperties;
  
  public function dispatch() {
    try {
      if (!$this->page)
        throw new NgnException('$this->page not defined. use $this->setPageData before dispatch()');
      if (isset($this->settings['mainTpl'])) $this->d['mainTpl'] = $this->settings['mainTpl'];
      parent::dispatch();
      if ($this->hasOutput) {
        $this->saveUserPage();
        $this->initOnlineUsers();
      }
    } catch(NgnException $e) {
      $this->extendTplData();
      IS_DEBUG === true ? $this->error404(
        'Ошибка',
        $e->getMessage().'<hr />'.getTraceText($e)
      ) : $this->error404('Страница не доступна', 'Приносим наши извенения');
    }
  }
  
  protected function initParamActionN() {
    $this->paramActionN = 1;
  }
  
  protected function init() {
    $this->pageLabels['new'] = '→ создание '.NgnMorph::cast(
      empty($this->settings['itemTitle']) ? 'запись' : $this->settings['itemTitle'],
      array('ЕД', 'РД')
    );
    $this->userId = Auth::get('id');
    $this->setActionParams();
    $this->initPriv();
    $this->initUserGroup();
    parent::init();
  }
  
  public $userGroup = false;
  
  protected function initUserGroup() {
    if (!Config::getVarVar('userGroup', 'enable')) return;
    if (($subdomain = O::get('SiteRequest')->getSubdomain()) === false) return;
    if (($this->userGroup = DbModelCore::get('userGroup', $subdomain, 'name')) === false)
      throw new NgnException('No such subdomain');
  }
  
  protected function afterInit() {
    parent::afterInit();
    $this->initMeta();
    $this->initHeadTitle();
  }
  
  public function setActionParams() {
    $this->actionParams['json_citySearch'] = array(
      array(
        'method' => 'request',
        'name' => 'mask'
      )
    );
    $this->actionParams['json_citySearch'] = array(
      array(
        'method' => 'request',
        'name' => 'mask'
      )
    );
    $this->actionParams['json_userSearch'] = $this->actionParams['json_citySearch'];
    $this->actionParams['updatePage'] = array('name' => 'title');
  }
  
  /**
   * Флаг раздрешает отображение неактивных разделов
   *
   * @var bool
   */
  public $allowNonActivePages = false;
  
  public function setPageTitle($title, $setEqualPath = false) {
    if (empty($title))
      throw new NgnException('Title can not be empty', 311);
    // Если определён заголовок в настройках meta-тегов, не учитывает переопределение
    //if (empty($this->d['pageMeta']['title'])) {
    //  
    //}
    $this->setHeadTitle($title);
    parent::setPageTitle($title);
    if ($setEqualPath) {
      $this->d['pathData'][count($this->d['pathData'])-1] = array(
        'title' => $title,
        'path' => Tt::getPath()
      );
    }
  }
  
  public function setPage($page) {
    $this->page = $page;
    if (!$this->page) {
      // Страница не существует
      $this->error404();
      return;
    }
    if (!$this->page['active'] and !$this->allowNonActivePages) {
      // Страница не активна
      $this->error404();
      return;
    }
    $this->d['pathData'] = $this->page['pathData'];
    $this->d['page'] = $this->page;
    $this->beforeInitSettings();
    $this->initSettings();
    $title = empty($this->page['fullTitle']) ?
      $this->page['title'] : $this->page['fullTitle'];
    $this->setHeadTitle($title);
    $this->setPageTitle($title);
    return $this;
  }
  
  protected function beforeInitSettings() {}

  private function initHeadTitle() {
    if (empty($this->d['pageMeta']['title'])) return;
    if ($this->d['pageMeta']['titleType'] == 'add') {
      $this->setHeadTitle($this->d['pageMeta']['title']);
    } else {
      $this->d['pageHeadTitle'] = $this->d['pageMeta']['title'];
    }
  }
  
  protected function setHeadTitle($title) {
    if (Config::getVarVar('layout', 'pageTitleFormat') == 1) {
      $this->d['pageHeadTitle'] = SITE_TITLE.' — '.$title;
    } else {
      $this->d['pageHeadTitle'] = $title.' — '.SITE_TITLE;
    }
  }
  
  protected function initMeta() {
    $this->d['pageMeta'] = db()->selectRow('SELECT * FROM pages_meta WHERE id=?d', $this->page['id']);
  }
  
  protected function initSettings() {
    $this->settings = $this->page['settings'] ? $this->page['settings'] : array();
    if (!empty($this->requiredSettings)) {
      foreach ($this->requiredSettings as $v) {
        if (!isset($this->settings[$v])) {
          //throw new NgnException("\$this->settings[$v] in '".get_class($this)."' class not defined. Page: ".getPrr($this->page));
        }
      }
    }
    if (!empty($this->defaultSettings)) {
      foreach ($this->defaultSettings as $k => $v) {
        if (empty($this->settings[$k])) {
          $this->settings[$k] = $v;
        }
      }
    }
    if ($this->settings) $this->d['settings'] = $this->settings;
  }
  
  private function saveUserPage() {
    if (!getConstant('SAVE_USER_PAGE')) return;
    UsersCore::save(
      $this->page['id'],
      $this->d['page']['title'],
      $this->d['page']['pathData']
    );
  }
  
  /**
   * Добавляет tpl-данные о пользователях находящихся онлайн
   */
  protected function initOnlineUsers() {
    if (!getConstant('SAVE_USER_PAGE')) return;
    $this->d['onlineUsers'] = UsersCore::getOnline();
  }
  
  protected function denialAuthorize() {
    $this->d['tpl'] = 'common/denialAuthorize';
  }
  
  protected function extendTplData() {
    if ($this->adminMode) return;
    if (empty($this->page)) return;
    Err::noticeSwitch(false);
    if (($path = Hook::getPath('before')) !== false) include $path;
    if (!empty($this->page['module']) and !PageModuleCore::isVirtual($this->page['module']) and ($paths = O::get('PageModuleInfo', $this->page['module'])->getFilePaths('afterAction.php')) !== false) include $paths[0];
    if (($path = Hook::getPath('pageNames/'.$this->page['name'])) !== false) include $path;
    if (($path = Hook::getPath('pageModules/'.$this->page['module'])) !== false) include $path;
    if (($path = Hook::getPath('controllers/'.$this->page['controller'])) !== false) include $path;
    if (($path = Hook::getPath('after')) !== false) include $path;
    Err::noticeSwitchBefore();
  }
  
  protected function prepareTplPath() {
    if ($this->adminMode) return;
    if (
    !empty($this->page['module']) and
    Tt::exists('pageModules/'.$this->page['module'].'/'.$this->d['tpl'])) {
      $this->d['tpl'] = 'pageModules/'.$this->page['module'].'/'.$this->d['tpl'];
    }
  }
  
  /**
   * Добавляет в ссылки пути ссылку на текущую страницу
   *
   * @param   string    Заголовок ссылки
   */
  public function setCurrentPathData($title) {
    $this->setPathData(Tt::getPath(), $title);
  }
  
  public function setPathData($path, $title) {
    if (!$title) return;
    $this->d['pathData'][] = array(
      'title' => $title,
      'link' => $path
    );
  }
  
  protected function setPathDataBeforeLast($path, $title) {
    $n = count($this->d['pathData']);
    for ($i=0; $i<$n; $i++) {
      if ($i == $n-1) {
        $newPathData[] = array(
          'title' => $title,
          'link' => $path
        );
      }
      $newPathData[] = $this->d['pathData'][$i];
    }
    $this->d['pathData'] = $newPathData;
  }
  
  public function setPathDataLast($title, $path = null) {
    $n = count($this->d['pathData'])-1;
    $this->d['pathData'][$n] = array(
      'title' => $title,
      'link' => $path ? $path : $this->d['pathData'][$n]['link']
    );
  }
  
  protected function resetPathData($path, $title) {
    $this->d['pathData'] = array(array(
      'title' => $title,
      'link' => $path
    ));
  }

  
  public function action_editPage() {
    $this->d['id'] = $this->page['id'];
    $this->d['title'] = $this->page['title'];
    $this->d['tpl'] = 'common/editPage';
  }
  
  public function action_updateTitle() {
    if (!isset($this->oReq->r['title']))
      throw new NgnException("\$this->oReq->r['title'] not defined");
    $oPages = new PagesAdmin();
    $oPages->updateTitle($this->page['id'], $this->oReq->r['title']);
    $this->redirect();
  }
  
  public function error404($title = 'Страница не найдена', $text = '') {
    parent::error404($title, $text);
    if ($this->hasOutput) {
      $this->setPathData(Tt::getPath(), $title);
      $this->setPageTitle($title);
    }
  }

  /**
   * Каталог с шаблонами для админки
   *
   * @var string
   */
  public $adminTplFolder;
  
  /**
   * Флаг определяет, что контроллер был вызван из админки
   *
   * @var bool
   */
  public $adminMode = false;
  
  /**
   * Использовать каталог с шаблонами по-умолчанию, не учитывая шаблон 
   * указанный в настройках раздела 
   *
   * @var bool
   */
  protected $useDefaultTplFolder = false;

  public function setAdminMode($flag) {
    if ($flag) {
      if (!$this->adminTplFolder)
        throw new NgnException('$this->adminTplFolder not defined');
      $this->tplFolder = 'admin/modules/pages/'.$this->adminTplFolder;
      $this->useDefaultTplFolder = true;
      $this->allowNonActivePages = true;
    } else {
      $this->tplFolder = 'dd';
      $this->useDefaultTplFolder = false;
      $this->allowNonActivePages = false;
    }
    $this->adminMode = $flag;
  }
  
  public function action_json_citySearch() {
    if (!$mask = $this->oReq->r['mask'] or !$this->oReq->r['name']) return;
    $mask = $mask.'%';
    $this->json['html'] = 
      getTpl_('common/searchResults', array(
        'name' => $this->oReq->r['name'],
        'items' => db()->selectCol(
          "SELECT title AS ARRAY_KEY, title FROM d?_citys WHERE active=1 AND title LIKE ? LIMIT 10",
          $mask)
      ));
  }
  
  /**
   * Массив, в котором каждая привилегия определяет те экшены (без layout-префиксов),
   * которые она разрешает
   *
   * @var   array
   */
  public $actionByPriv = array(
    'view' => array('default'),
    'moder' => array('edit', 'new', 'moveForm', 'move', 'delete', 'activate', 'deactivate', 'publish'),
    'edit' => array('edit', 'update', 'delete', 'move', 'activate', 'deactivate', 'deleteFile'),
    'editOnly' => array('edit', 'delete'),
    'admin' => array('updateDirect', 'deleteGroup', 'deleteFile'),
    'create' => array('new'),
    'sub_edit' => array('sub_edit', 'sub_update', 'sub_delete', 'sub_activate', 'sub_deactivate'),
    'sub_create' => array('sub_create')
  );
  
  public $privByAction;
  
  public $allowedActions;
  
  /**
   * Определяет возможность редактирования текущим пользователем данного раздела/записи
   * см. дальнейшую реализацию метода в наследуемых классах
   *
   */
  protected function initPriv() {
    $this->initActionsByPriv();
    $this->_initPriv();
    $this->d['privAuth'] = $this->priv->getAuthPriv();
    $this->d['priv'] = $this->priv;
    $this->initAllowedActions();
  }
  
  /**
   * @var PagePriv
   */
  public $priv;
  
  protected function _initPriv() {
    $this->priv = new PagePriv($this->page, $this->userId);
  }
  
  // Дозволеные экшены
  protected function _initAllowedActions() {
    $this->allowedActions = array();
    foreach (array_keys($this->priv->r) as $priv)
      if (isset($this->actionByPriv[$priv]))
        foreach ($this->actionByPriv[$priv] as $action)
          $this->allowedActions[] = $action;
  }
  
  protected function initAllowedActions() {
    $this->_initAllowedActions();
    $this->d['allowedActions'] = $this->allowedActions;
  }
  
  protected function setModers() {
    $this->d['moders'] = $this->oPriv->getUsers($this->page['id'], 'edit');
  }
  
  /**
   * Определяет дополнительные привилегии после инициализации
   *
   * @param  string  Имя привилегии
   * @param  bool    Флаг "разрешено/запрещено"
   */
  protected function setPriv($name, $flag) {
    $this->priv[$name] = $flag;
    $this->d['priv'][$name] = $flag;
    $this->initAllowedActions();
  }
  
  protected function setPrivs(array $names, $flag) {
    foreach ($names as $name) {
      $this->priv[$name] = $flag;
      $this->d['priv'][$name] = $flag;
    }
    $this->initAllowedActions();
  }
  
  /**
   * Разрешить ли данный экшен
   *
   * @param   string    Имя экшена
   * @return  bool
   */
  protected function allowAction($action) {
    $action = $this->clearActionPrefixes($action);
    if (!isset($this->priv)) return true;
    // Если не существует названия привилегии для этого экшена, раздрешаем экшн
    if (!isset($this->privByAction[$action]))
      // Если для экшена нет привилегий, значит по умолчанию он разрешен
      return true;
    return in_array($action, $this->allowedActions);
  }
 
  protected function initActionsByPriv() {
    foreach ($this->actionByPriv as $priv => $actions) {
      foreach ($actions as $action) {
        $this->privByAction[$action] = $priv;
      }
    }
  }
  
  protected $allowAuthorized = false;
  protected $allowAuthorizedActions = array();
  
  protected function action() {
    if (empty($this->priv['view'])) {
      $this->error404();
      return;
    }
    if ($this->allowAuthorized and !Auth::get('id')) {
      $this->d['tpl'] = 'denialAuthorize';
      return;
    } elseif ($this->allowAuthorizedActions and
              in_array($this->action, $this->allowAuthorizedActions)) {
      $this->d['tpl'] = 'denialAuthorize';
      return;
    } elseif (!$this->allowAction($this->action) or isset($_GET['editNotAllowed'])) {
      $this->error404('Действие запрещено');
      return;
    }
    parent::action();
  }
  
  protected function initAction() {
    // Если в настройках раздела определен экшн по умолчанию
    if (!empty($this->settings['defaultAction'])) {
      $this->defaultAction = $this->settings['defaultAction'];
    }
    parent::initAction();
  }

  protected $disablePageLog = false;
  
  protected function afterAction() {
    if (!($userId = Auth::get('id'))) $userId = 0;
    $users = Config::getVar('hideOnlineStatusUsers', true);
    if ($users and in_array($userId, $users)) return;
    if (!$this->disablePageLog) {
      db()->query(
        'REPLACE INTO users_pages SET dateCreate=?, pageId=?d, title=?, url=?, userId=?d, path=?',
        dbCurTime(), $this->page['id'], $this->d['pageTitle'], $_SERVER['REQUEST_URI'],
        $userId, $this->page['path']);
    }  
  }
  
  public $pageLabels = array(
    'edit' => '(редактирование)'
  );
  
  protected function getPageLabel() {
    return isset($this->pageLabels[$this->action]) ? ' '.$this->pageLabels[$this->action] : null;
  }
  
  /**
   * Удаляет раздел и все прикрепленные к нему объекты
   */
  public function deletePage() {
  }
  
  public function action_blocks() {
    $this->d['blocks'] = PageBlockCore::getBlocks($this->page['id']);
    $this->d['tpl'] = 'common/pageBlocksOneCol';
  }

  // ====================== Default Inctance Data ==========================
  
  static public function getVirtualPage() {
    throw new NgnException('Method "getVirtualPage" not realized in class '.get_called_class());
  }
  
}
