<?php

/**
 * Page Actions
 * Контроллер вывода страницы.
 * Что делает:
 * 1. init() - вызывается до любых действий, осуществляемых в контроллере
 * 2. action() - осуществляет действия в зависимости от значения $this->oReq->r['action'] или параметров, 
 *    заробранных Req'ом
 * 3. defaultAction() - вызывается если $this->isDefaultLayout = true и подготавливает данные вывода шаблона
 *
 */
abstract class CtrlCommon extends Options {

  /**
   * Массив с данными для шаблона
   *
   * @var array
   */
  public $d = array();

  /**
   * Выводить ли страницу или действие происходит без вывода
   *
   * @var bool
   */
  public $hasOutput;

  /**
   * Определяет существование запрашиваемого экшена
   *
   * @var bool
   */
  public $isAction = false;

  /**
   * Запскать ли функию подготовки данных для страницы без экшенов
   *
   * @var string
   */
  public $isDefaultAction = true;

  /**
   * Текущий экшн страницы (берётся из $this->oReq->r['action'])
   *
   * @var string
   */
  public $action;
  
  /**
   * Экшн, который вызывается по умолчанию
   *
   * @var string
   */
  protected $defaultAction;

  /**
   * Префикс экшена (ajax, json, ...)
   * 
   * Так к слову: Экшеном является строка состоящая из префикса и имени,
   * соединенных подчеркиванием, а не только одно имя без префикса
   *
   * @var string
   */
  public $actionPrefix;

  /**
   * Имя экшена без префикса
   * 
   * @var string
   */
  public $actionBase;
  
  /**
   * Параметры строки запроса
   */
  public $params;

  /**
   * Экшены с этими префиксами будут отключать вывод главного шаблона
   */
  public $noLayoutPrefixes = array(
    'ajax', 
    'json', 
    'rss', 
    'xml'
  );

  /**
   * Флаг определяет, является ли текущий запрос JSON-запросом
   *
   * @var bool
   */
  public $isJson;
  
  protected $isAjax;
  
  public $isError404 = false;
  
  /**
   * Данные для формирования JSON-формата
   *
   * @var mixed
   */
  public $json;

  /**
   * ID пользователя
   *
   * @var integer
   */
  public $authUserId;

  public $allowRedirect = true;

  public $paramActionN = 2;

  public $actionDisabled = false;

  protected $afterActionDisabled = false;

  public $allowRequestAction = true;

  // public, потому что должны быть доступны из sub-контроллера
  public $ajaxSuccess;
  public $ajaxOutput;
  
  /**
   * Пример:
   * array(
   *   array(
   *     'action' => 'actionName',
   *     'ajaxTpl' => 'common/form'
   *   )
   * )
   *
   * @var array
   */
  protected $html2ajaxActions = array();
  
  public $tplTrace = array();
  
  /**
   * @var Dispatcher
   */
  public $oDispatcher;
  
  /**
   * @var Req
   */
  public $oReq;
  
  public final function __construct(
  Dispatcher $oDispatcher,
  array $options = array()
  ) {
    $this->oDispatcher = $oDispatcher;
    $this->setOptions($options);
    $this->oReq = empty($this->options['oReq']) ? O::get('Req') : $this->options['oReq'];
    $this->d['oController'] = $this;
    if (!isset($this->defaultAction)) {
      if (method_exists($this, 'action_json_default'))
        $this->defaultAction = 'json_default';
      elseif (method_exists($this, 'action_ajax_default'))
        $this->defaultAction = 'ajax_default';
      else
        $this->defaultAction = 'default';
    }
  }
  
  public $subControllers = array();
  
  protected function addSubController(SubPa $oSubPa) {
    $this->subControllers[$oSubPa->getName()] = $oSubPa;
  }
  
  public function __call($method, array $param = array()) {
    foreach ($this->subControllers as $oSubPa) {
      if (is_callable(array($oSubPa, $method))) {
        if ($oSubPa->disable) return;
        return call_user_func_array(array($oSubPa, $method), $param);
      }
    }
    if (method_exists($this, $method)) {
      return call_user_func_array(array($this, $method), $param);
    } else {
      throw new NoMethodException($method);
    }
  }
  
  private  function callDirect($method, array $param = array()) {
    call_user_func_array(array($this, $method), $param);
  }
  
  public function beforeAction() {
    if ($this->isError404) return;
    $this->initParams();
    $this->setTheme();
    $this->setAuthUserId();
    $this->initParamActionN();
    $this->addSubControllers();
    $this->initAction();
    $this->setPostAction();
    $this->setActionParams();
    $this->beforeInit();
    $this->init();
    $this->afterInit();
    $this->initSubControllers();
  }
  
  protected function addSubControllers() {}
  
  final protected function initSubControllers() {
    foreach ($this->subControllers as $o) $o->init();
  }

  /**
   * Конструктор
   *
   * @param mixed   Все данные текущей страницы
   * @param array   Параметры запроса
   * @param string  Шаблон вывода по умолчанию
   * @param string  Действие
   */
  public function dispatch() {
    if ($this->isError404) return;
    $this->beforeAction();
    if (!$this->actionDisabled) {
      $this->action();
      $this->setDefaultTpl();
      if (!$this->afterActionDisabled)
        $this->afterAction();
    }
    $this->extendTplData();
    $this->prepareTplPath();
  }

  protected function setTheme() {}

  protected function beforeInit() {}

  protected function afterInit() {}

  protected $extendTplNames = array();

  /**
   * Определяем имя файла, который будет добавлять дополнительные 
   * данные в $this->d
   *
   * @param   string  Имя файла
   */
  protected function setExtendTplName($name) {
    $this->extendTplNames[] = $name;
  }

  protected function setExtendTplNames($names) {
    $this->extendTplNames = $names;
  }

  /**
   * Добавляет дополнительные данные в $this->d
   */
  protected function extendTplData() {}
  
  protected function prepareTplPath() {
  }

  /**
   * Вывод шаблона этого контроллера
   */
  public function getOutput() {
  	if ($this->isJson) {
      // JSON OUTPUT HERE
      if (!empty($this->oReq->r['ifr']))
        return '<textarea id="json">'.json_encode($this->json).'</textarea>';
      else {
        if (JSON_DEBUG !== true) header('Content-type: application/json');
        return json_encode($this->json);
      }
      return;
    } else {
      if (isset($this->ajaxSuccess) or isset($this->ajaxOutput)) {
        header("Content-type: text/html; charset=".CHARSET);
        if (isset($this->ajaxSuccess))
          return $this->ajaxSuccess ? 'success' : 'failed';
        else
          return $this->ajaxOutput;
      }
    }
    if (!$this->hasOutput) return '';
    // Main layout headers
    header("Content-type: text/html; charset=".CHARSET);
    if (empty($this->d['tpl'])) {
      throw new NgnException(
        "<b>\$this->d['tpl']</b> in <b>".get_class($this)."</b> class not defined");
    }
    $html = Tt::getTpl($this->d['mainTpl'], $this->d);
    $this->d['processTime'] = getProcessTime();
    return $html;
  }

  /**
   * Здесь должны происходить операции, необходимые до вызова $this->action()
   */
  protected function init() {}

  /**
   * Должна определять параметры экшенов
   * ===================================
   * 
   * Пример использования для _REQUEST параметра:
   * 
   * $this->actionParams['json_citySearch'] = array(
   *   'name' => 'mask',
   *   'notRequired' => 1
   * )
   * 
   * Пример использования для path-параметра:
   * 
   * $this->actionParams['test'] = array(
   *   'n' => 5
   * )
   * 
   * По умолчанию все параметры обязательны.....
   * Метод по умолчанию - param
   *
   */
  public function setActionParams() {
  }

  protected function setAuthUserId() {
    $this->authUserId = Auth::get('id');
    $this->d['authorized'] = $this->authUserId ? true : false;
  }

  protected function initParams() {
    $this->d['curUrl'] = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $this->params = $this->d['params'] = $this->oReq->params;
    $this->d['base'] = $this->oReq->getAbsBase();
  }
  
  protected function setActionIfNotDefined($action) {
    if (!isset($this->action))
      $this->setAction($action);
  }
  
  protected function setActionIfNoRequestAction($action) {
    if (!isset($this->oReq->r['action']))
      $this->setAction($action);
  }
  
  protected $subAction = false;
  
  protected function setAction($action) {
    if (!preg_match('/^[A-Za-z_0-9]+$/', $action))
      throw new NgnException("Action name '$action' not allowed. Req: ".getPrr($this->oReq->r));
    if (Misc::hasPrefix('sub_', $action)) {
      $this->subAction = true;
      $action = Misc::removePrefix('sub_', $action);
    }
    $this->d['action'] = $this->action = $action;
    $this->isJson = false;
    $this->isAjax = false;
    $this->hasOutput = true;
    // Для этих типов экшена, вывод основного шаблона запрещается
    // Это значит что вывод будет осуществляться в самих экшенах
    if (($a = $this->parsePrefixedAction($this->action)) !== false) {
      $this->hasOutput = false;
      $this->actionPrefix = $a[0];
      $this->actionBase = $a[1];
      if ($this->actionPrefix == 'json') {
        $this->isJson = true;
      } elseif ($this->actionPrefix == 'ajax') {
        $this->isAjax = true;
      }
    }
    if (!R::get('plainText')) // переопределяем только, если PLAINT TEXT режим выключен 
      R::set('plainText', ($this->actionPrefix == 'ajax' or $this->actionPrefix == 'json'));
  }

  protected function parsePrefixedAction($action) {
    if (preg_match('/('.implode('|', $this->noLayoutPrefixes).')_(.+)/', $action, $m)) {
      return array($m[1], $m[2]);
    }
    return false;
  }

  protected function initAction() {
    // Определяем параметр из массива $this->oReq->r, если разрешено
    if ($this->allowRequestAction) {
      // Экшн из $this->oReq->r'а имеет приемственность, поэтому, если он определём,
      // переопределяем полюбому
      if (/*! $this->action and */isset($this->oReq->r['action'])) {
        if (empty($this->oReq->r['action']))
          throw new EmptyException("\$this->oReq->r['action']");
        $this->setAction($this->oReq->r['action']);
        $possibleAction = $this->oReq->r['action'];
      }
    }
    // Определяем action, получая его из параметров строки запроса
    if (!isset($this->action) and ($paramAction = $this->getParamAction()) !== false) {
      if ($this->getActionObject($paramAction) !== false)
        $this->setAction($paramAction);
    }
    if (!isset($this->action)) $this->setAction($this->defaultAction);
  }

  protected function getNumParam($n) {
    if(!isset($this->params[$n]))
      throw new NgnException('$this->params[3] not defined');
    if (!is_numeric($this->params[$n]))
      throw new NgnException('$this->params[3] is not numeric');
    return $this->params[$n];
  }
  
  protected function getParam($n) {
    if(!isset($this->params[$n]))
      throw new NgnException('$this->params['.$n.'] not defined');
    return $this->params[$n];
  }
  
  protected function getParamAction() {
    if (!isset($this->paramActionN)) return false;
    return isset($this->params[$this->paramActionN]) ? $this->params[$this->paramActionN] : false;
  }

  /**
   * Должна определять $this->paramActionN
   */
  protected function initParamActionN() {}

  // Экшн для формы шаблона
  protected function setPostAction() {
    if ($this->action == 'edit' or $this->action == 'update')
      $this->d['postAction'] = 'update';
    elseif ($this->action == 'new' or $this->action == 'create')
      $this->d['postAction'] = 'create';
  }

  protected function setDefaultTpl() {
    if (!isset($this->d['tpl']) or ! $this->d['tpl'])
      $this->d['tpl'] = 'default';
    if (!isset($this->d['mainTpl']) or ! $this->d['mainTpl'])
      $this->d['mainTpl'] = 'main';
  }

  protected function afterAction() {}
  
  /*
  protected function formAction() {
    $actionMethod = 'action_'.$this->action;
    if (method_exists($this, $actionMethod)) {
      if (($oF = $this->$actionMethod()) === null or !is_a($oF, 'Form')) return false;
      $this->d['form'] = $oF->html();
      if (Misc::hasPrefix('ajax_', $this->action)) $this->ajaxFormAction($oF, $updated);
      else if ($updated) $this->redirect();
      return true;
    }
    if (!Misc::hasPrefix('ajax_', $this->action)) return false;
    $actionMethod = 'action_'.Misc::removePrefix('ajax_', $this->action);
    if (!method_exists($this, $actionMethod)) return false;
    $this->action = Misc::removePrefix('ajax_', $this->action);
    if (($oF = $this->$actionMethod()) === null or !is_a($oF, 'Form')) return false;
    $this->ajaxFormAction($oF);
    return true;
  }
  */
  
  protected function ajaxFormAction(Form $oF) {
    $oF->disableSubmit = true;
    $this->ajaxOutput = Tt::getTpl('common/form', array('form' => $oF->html()));
  }
  
  protected $actionMethod;
  
  protected $actionObjects;
  
  protected function getActionObject($action) {
    $actionMethod = 'action_'.$action;
    if (method_exists($this, $actionMethod)) return $this;
    if (!empty($this->subControllers)) {
      foreach ($this->subControllers as $oC) {
        if (method_exists($oC, $actionMethod)) {
          return $oC;
        }
      }
    }
    return false;
  }

  /**
   * Вызываются экшены
   */
  protected function action() {
    if ($this->isError404) return;
    if (!$this->action) throw new NgnException('$this->action not defined');
    $this->checkActionParams($this->action);
    $actionMethod = 'action_'.$this->action;
    $oAction = $this->getActionObject($this->action);
    if ($oAction !== false) {
      $this->isAction = true;
      if ($this->isJson) {
        $oF = $this->actionJson($oAction, $actionMethod);
        if (is_object($oF) and is_a($oF, 'Form'))
          $this->jsonFormAction($oF);
      } else {
        //pr(array(get_class($oAction), $actionMethod));
        $oAction->$actionMethod();
      }
    } else {
      // Меняем флаги на формат обычного экшена с лейаутом
      $this->hasOutput = true;
      $this->isJson = false;
      $this->actionNotFound($actionMethod);
    }
  }
  
  /*
  protected function action_() {
    if ($this->isError404) return;
    if (!$this->action) throw new NgnException('$this->action not defined');
    $this->checkActionParams($this->action);
    $actionMethod = 'action_'.$this->action;
    if (method_exists($this, $actionMethod)) {
      $this->isAction = true;
      if ($this->isHtml2ajaxAction) {
        $this->actionHtml2ajax($actionMethod);
      } elseif ($this->isJson) {
        $oF = $this->actionJson($actionMethod);
        if (is_object($oF) and is_a($oF, 'Form'))
          $this->jsonFormAction($oF);
      } else {
        $oF = $this->$actionMethod();
        if ($this->isAjax and is_object($oF) and is_a($oF, 'Form'))
          $this->ajaxFormAction($oF);
      }
    } else {
      $this->hasOutput = true;
      $this->isJson = false;
      $this->actionNotFound($this->actionMethod);
    }
  }
  */
  
  protected function actionJson($oAction, $actionMethod) {
    ini_set('html_errors', false);
    // Если это JSON запрос, выключаем отображение ошибок и 
    // сохраняем последнюю (если она есть) в json-массив
    R::set('showErrors', false); // --- Отключаем показ ошибок
    try {
      $r = $oAction->$actionMethod();    // --- Выполняем экшн
    } catch (Exception $e) {     // --- Лобим исключение, записываем его в json
      // Формирование массива error для исключений необходимо делать здесь, потому что
      // exceptionHandler срабатывает только, если исключение не поймано.
      // В конструкторе NgnException нельзя делать создание этого массива, т.к.
      // эксепшены могут быть созданы вендорными классами, которые не унеаследованы от
      // NgnException
      $this->json['error'] = array(
        'message' => $e->getMessage(),
        'code' => $e->getCode(),
        'file' => $e->getFile(),
        'trace' => getTraceText($e, false)
      );
      LogWriter::v('errors', 'exception: '.$e->getMessage(), getFullTrace($e));
      return;
    }
    if (($lastError = R::get('lastError')) !== false)
      $this->json['error'] = $lastError; // --- Добавляем ошибку, если она есть, в json
    return $r;
  }

  /*
  protected function actionHtml2ajax($actionMethod) {
    $this->$actionMethod();
    if (!($ajaxTpl = Arr::get_value($this->html2ajaxActions, 'action', $this->action, 'ajaxTpl')))
      throw new NgnException(
        'ajaxTpl not defined in array $this->html2ajaxActions for action '.
        $this->action.': '.getPrr($this->html2ajaxActions));
    print Tt::getTpl($ajaxTpl, $this->d);
  }
  */
  
  protected function getActionMethod() {
    return 'action_' . $this->action;
  }

  protected function actionNotFound($actionMethod) {
    throw new NgnException(
      'Method <b>' . get_class($this) . '::' . $actionMethod . '</b> not found.');
  }

  // --------------------------------
  // Проверка параметров для экшенов
  // --------------------------------
  

  public $actionReqParams;

  public $actionPathParams;

  /**
   * Enter description here...
   *
   * @param unknown_type $action
   * @param unknown_type $name
   * @param unknown_type text/num/array/array2
   */
  public function addActionReqParam($action, $name, $type = 'text') {
    $this->actionReqParams[$action][] = array(
      'name' => $name, 
      'type' => $type
    );
  }

  public function addActionReqParams($action, $params) {
    $this->actionReqParams[$action] = 
      Arr::append($this->actionReqParams[$action], $params);
  }

  public function addActionPathParam($action, $n) {
    $this->actionPathParams[$action][] = $n;
  }

  /**
   * Проверяем наличие необходимых параметров для выполнения экшена
   * 
   * @todo  Экшены проверяются только для основного контроллера..
   *        Саб-конттроллер же пущен по боку
   */
  private function checkActionParams($action) {
    if (isset($this->actionReqParams[$action])) {
      foreach ($this->actionReqParams[$action] as $param) {
        // Проверяем наличие нужного параметра в массиве $this->oReq->r
        if (! isset(
          $this->oReq->r[$param['name']]))
          throw new NgnException("\$this->oReq->r[{$param['name']}] required");
        if ($param['type'] == 'num')
          if (! is_numeric($this->oReq->r[$param['name']]))
            throw new NgnException(
              "\$this->oReq->r[{$param['name']}] in not numeric.<br />".
              "<b>Controller:</b> ".get_class($this).", <b>Action:</b> $action, ".
              "<b>Param:</b> ".$param['name'].'. $this->oReq->r: '.getPrr($this->oReq->r));
          elseif ($param['type'] == 'array')
            if (! is_array($this->oReq->r[$param['name']]))
              throw new NgnException(
                "\$this->oReq->r[{$param['name']}] is not an array");
      }
    }
    if (isset($this->actionPathParams[$action])) {
      foreach ($this->actionPathParams[$action] as $param) {
        // Проверяем наличие нужного параметра в массиве параметров запроса
        if (! $this->params[$param]) {
          throw new NgnException(
            'Path param "' . str_repeat('/param', 
              $param) . '/{x}" required');
        }
      }
    }
  }

  // --------------------------------------------------------------------
  

  public function action_default() {}

  //  function actionNotExists() {    Err::warning("Method '".get_class($this)."->action_{$this->action}' not exists");  }
  

  /**
   * Перенаправляет страницу, отключая при это вывод
   *
   * @param   string    null - редирект на страницу без QUERY_STRING
   *                    'referer' - редирект на реферер этой страницы
   *                    все остальные значение - ссылка для редиректа
   */
  public function redirect($path = null) {
    if (!$this->allowRedirect) return;
    $this->hasOutput = false;
    if ($path == 'referer') {
      if (isset($this->oReq->r['referer'])) {
        $path = $this->oReq->r['referer'];
      } else {
        $path = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
      }
    } elseif ($path == 'fullpath') {
      redirect(Tt::getPath().($_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : ''));
      return;
    }
    if ($path) {
      if ($path == Tt::getPath() and !count($_GET) and !count($_POST))
        print('Can not redirect to itself');
      else {
        redirect($path);
      }
    } elseif (count($_GET) or count($_POST)) {
      redirect(Tt::getPath());
    } else
      print('Can not redirect to itself');
  }

  protected function getName() {
    return str_replace('Ctrl', '', get_class($this));
  }

  protected function setPageTitle($title) {
    $this->d['pageTitle'] = $title;
  }

  protected function getActionMethods() {
    $methods = array();
    foreach (get_class_methods(get_class($this)) as $method) {
      if (preg_match('/action_(.*)/', $method)) {
        $methods[] = $method;
      }
    }
    return $methods;
  }

  protected function getActions() {
    $methods = array();
    foreach (get_class_methods(get_class($this)) as $method) {
      if (preg_match('/action_(.*)/', $method, $m)) {
        $methods[] = $m[1];
      }
    }
    return $methods;
  }

  ///////////// Actions ////////////
  

  public function action_json_userSearch() {
    if (! $mask = $this->oReq->r['mask'] or ! $name = $this->oReq->r['name'])
      return;
    $this->json['html'] = Tt::getTpl('common/searchResults', 
      array(
        'name' => $name, 
        'items' => UsersCore::searchUser($mask)
      ));
  }

  public function action_json_pageSearch() {
    if (! $mask = $this->oReq->r['mask'])
      return;
    $this->json['html'] = Tt::getTpl('common/searchResults', 
      array(
        'name' => 'pageId', 
        'items' => Pages::searchPage($mask)
      ));
  }

  public function action_json_userAutocomplete() {
    $mask = $this->oReq->rq('mask');
    if ($mask[0] == '_') {
      $this->json = array(
        ALL_USERS_ID => 'Все пользователи',
        REGISTERED_USERS_ID => 'Зарегистированые пользователи'
      );
      return;
    }
    $this->json = db()->selectCol("
      SELECT id AS ARRAY_KEY, login FROM users WHERE
      login LIKE ? ORDER BY id LIMIT 10", 
      $mask.'%');
  }

  public function action_json_pageItemsAutocomplete() {
    $this->json = DbModelPages::searchPage($this->oReq->r['mask'], "pages.controller='items'");
  }
  
  public function action_json_pageAlbumsAutocomplete() {
    $this->json = DbModelPages::searchPage($this->oReq->r['mask'], "pages.controller='albums'");
  }

  public function action_json_pageAutocomplete() {
    $this->json = DbModelPages::searchPage($this->oReq->r['mask']);
  }
  
  public function action_json_folderAutocomplete() {
    $this->json = DbModelPages::searchFolder($this->oReq->r['mask']);
  }
  
  /**
   * Очищает экшн от layout-префиксов
   *
   * @param   string  action
   * @return  string  очищенный action
   */
  protected function clearActionPrefixes($action) {
    foreach ($this->noLayoutPrefixes as $v)
      $noLayoutPrefixes[] = $v.'_';
    $action = str_replace($noLayoutPrefixes, '', $action);
    if (isset($action[0]) and $action[0] == '_')
      $action = substr($action, 1, strlen($action));
    return $action;
  }
  
  protected function error($msg) {
    $this->isError404 = true;
    $this->hasOutput = false;
    Err::warning($msg);
  }

  protected function getPjLastStepKey(PartialJob $oPJ) {
    return $oPJ->getId().'LastStep';
  }
  
  protected function getPjLastStep(PartialJob $oPJ) {
    return Settings::get($this->getPjLastStepKey($oPJ));
  }
  
  protected function actionJsonPJ(PartialJob $oPJ) {
    $settingsKey = $this->getPjLastStepKey($oPJ);
    $step = $this->oReq->rq('step');
    $this->json['step'] = $step;
    if (!$step and ($_step = Settings::get($settingsKey))) {
      // Если 0-й шаг, начинаем с последнего сохраненного шага
      $step = $_step + 1;
    }
    $this->json = $oPJ->stepData($step);
    try {
      $oPJ->makeStep($step);
    } catch (Exception $e) {
      if ($e->getCode() == 1040) {
        // Шаг больше максимально возможного.
        // Значит по какой-то причине предыдущий шаг не был успешно завершен
        // Завершаем
        $oPJ->complete();
        return;
      }
      // 'continueErrorCodes' - коды ошибок, для которых включена ф-я "продолжить"
      // Если эти коды существуют
      // Проверяем выброшеное исключение на наличие в них
      elseif (
      !empty($this->oReq->r['continueErrorCodes']) and
      in_array($e->getCode(), $this->oReq->r['continueErrorCodes'])) {
        // И, если оно там есть, переходим к следующему шагу 
        Settings::set($settingsKey, $step);
      }
      // И выбрасываем ошибку, она нам ещё понадобиться в формировании ответного json-массива
      throw $e;
    }
    Settings::set($settingsKey, $step);
  }
  
  protected function cleanupPJStep(PartialJob $oPJ) {
    Settings::delete($this->getPjLastStepKey($oPJ));
  }
  
  public function error404($title = 'Страница не найдена', $text = '') {
    header('HTTP/1.0 404 Not Found');
    if (!$this->hasOutput) {
      print "<h1>$title</h1>$text";
      return;
    }
    $this->setDefaultTpl();
    //$this->d = array();
    $this->isDefaultAction = false;
    // Если в результате экшенов получилось так, что была определена 404 страница,
    // это значит, что экшен не прошел успешно и действий никаких после него 
    // вызывать не надо
    $this->afterActionDisabled = true;
    $this->isError404 = true;
    $this->d['tpl'] = 'errors/404';
    $this->d['text'] = $text;
  }
  
  protected function jsonFormAction(Form $oF) {
    $oF->disableSubmit = true;
    $this->json['form'] = Tt::getTpl('common/form', array('form' => $oF->html()));
    if (!empty($oF->options['title'])) $this->json['title'] = $oF->options['title'];
    $this->json['submitTitle'] = $oF->options['submitTitle'];
    return $oF;
  }
  
  protected function actionJsonFormUpdate(Form $oF) {
    if ($oF->update()) return true;
    return $oF;
  }
  
  protected function processForm(Form $oF) {
    $this->d['tpl'] = 'common/form';
    if ($oF->update()) return true;
    $this->d['form'] = $oF->html();
     return false;
  }

}
