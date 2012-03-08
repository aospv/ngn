<?php

class CtrlPageDdItems extends CtrlPageDd {

  protected $defaultAction = 'list';
  
  public $order;

  public $editTime = 9999999999;

  public $adminTplFolder = 'items';

  //////////////////// UserItems ////////////////////

  /**
   * Порядковы номер параметра, отвечающего за ключевое слово фильтра
   * См. $this->setFilderParam
   *
   * @var integer
   */
  public $paramFilterN = 1;

  /**
   * Флаг определяет необходимость жесткого использования фильтров
   *
   * @var bool
   */
  public $strictFilters = true;
  
  protected function initAction() {
    if ($this->isListParams()) $this->defaultAction = 'list';
    parent::initAction();
  }

  protected function init() {
    if ($this->page->getS('ownerMode') == 'userGroup' and !O::get('SiteRequest')->getSubdomain())
      throw new NgnException('Controller must work only with subdomains');
    $this->d['isItemsController'] = true;
    if (!empty($this->page['settings']['editTime']))
      $this->editTime = $this->page['settings']['editTime'];
    $this->itemsCacheEnabled = empty($this->page['settings']['disableItemsCache']);
    /**
     * @todo необходимо сделать ревизию и убрать из init()
     * те функции, что вызываются только для получания одной или нескольких записей
     */
    if (!isset($this->action)) {
      if (isset($this->tag))
        $this->setAction('tag');
      elseif (isset($this->tags))
        $this->setAction('tags');
    }
    ////////////////////////////////////////////////////
    parent::init();
    $this->d['ddType'] = 'ddObjects'; // Необходимо для шаблона модераторского-меню
    $this->d['tpl'] = 'default';
    // Это не просто так тут. оно не может быть в методе initAction(), потому что
    // $this->itemId определяется только в init()
    if ($this->action == $this->defaultAction and $this->itemId)
      $this->setAction('showItem');
    // Порядок вывода
    if (isset($this->params[1]) and $this->params[1] == 'o') {
      $allowedOrderFields = array(
        'dateCreate', 
        'dateUpdate', 
        'datePublish', 
        'commentsUpdate', 
        'title', 
        'commentsCount'
      );
      if (in_array($this->params[2], $allowedOrderFields)) {
        $this->order = $this->params[2] . ($this->params[3] == 'a' ? ' ASC' : ' DESC');
      }
    }
    if (!isset($this->order) and !empty($this->page['settings']['order']))
      $this->setOrder($this->page['settings']['order']);
    /////////////////////
    // преобразование пути происходив в экшене. так что нужно заменить последний элемент пути до выполнения экшенов
    if ($this->userGroup) $this->d['pathData'][0] = array(
      'title' => $this->userGroup['title'],
      'link' => SiteRequest::url($this->userGroup['name']).'/'.DbModelCore::get('pages', 'userGroupHome', 'module')->r['path']
    );
      
    $this->initItemsManager();
    $this->initPagination();
    $this->initTagsTplCommonData();
    $this->initCalendarData();
    $this->initDateRange2RangeFilter();
    $this->initOrderByParams();
    if ($this->itemId and !empty($this->page['settings']['comments'])) {
      $this->addSubController(new SubPaComments($this, $this->page['id'], $this->itemId));
    }
    
    $this->initItems();
  }
  
  protected function addSubControllers() {
    if ($this->page['slave']) $this->addSubController(new SubPaDdSlave($this));
  }
  
  protected function initDateRange2RangeFilter() {
    if (
    empty($this->page['settings']['dateFieldBegin']) or 
    empty($this->page['settings']['dateFieldEnd'])
    ) return;
    
    $m = array();
    if (preg_match('/d2\.(.*)-(.*)/', $this->params[1], $m)) {
      $from = date_format(new DateTime($m[1]), 'Y-m-d');
      $to = date_format(new DateTime($m[2]), 'Y-m-d');
    } else {
      $from = date('Y-m-d');
      $to = '9000-01-01';
    }
    //if (empty($this->page['settings']['futureItems']))
    $this->oManager->oItems->cond->addRange2RangeFilter(
      $this->page['settings']['dateFieldBegin'],
      $this->page['settings']['dateFieldEnd'],
      $from,
      $to
    );
  }
  
  /**
   * Определяет сортировку
   *
   * @param string Строка сортировки. Пример: 'title DESC'
   */
  protected function setOrder($order) {
    $field = preg_replace('/([a-zA-Z]*)\s+(ASC|DESC|)/', '$1', $order);
    if (!$field) {
      throw new NgnException('$field not defined');
      return;
    }
    if (!$this->strName) return;
    $this->order = $order;
    $this->d['orderField'] = $field;
  }

  private $shortOrderParams = array(
    'create' => 'dateCreate', 
    'update' => 'dateUpdate', 
    'publish' => 'datePublish', 
    'comments' => 'commentsUpdate'
  );

  public $orderField;

  protected function initOrderByParams() {
    // Пробегаемся по всем параметрам и смотри нет ли чего похожего на сортировку
    foreach ($this->params as $param) {
      if (preg_match('/^(oa|o)\.([a-zA-Z]+)/', $param, $m)) {
        if (isset($this->shortOrderParams[$m[2]])) {
          $field = $this->shortOrderParams[$m[2]];
        } else {
          $field = $m[2];
        }
        $this->orderField = $field;
        if ($m[1] == 'o') {
          // Order DESC. Пример: o=dateCreate
          $this->setOrder($field.' DESC');
        } else {
          // Order ASC. Пример: oa=dateCreate
          $this->setOrder($field.' ASC');
        }
        // Если присутствует условие для сортировки, значит нужно вызывать экшн 'list'
        $this->setActionIfNoRequestAction('list');
      }
    }
  }

  protected function initPagination() {
    if (!empty($this->page['settings']['n'])) {
      $this->oManager->oItems->setN($this->page['settings']['n']);
    } else {
      $this->oManager->oItems->setN(Config::getSubVar('dd', 'itemsN'));
    }
  }

  protected function afterInit() {
    parent::afterInit();
    $this->setPrivAfterInit();
  }
  
  /**
   * Создатель текущей страницы
   * 
   * @var DbModelUsers
   */
  protected $pageUser;

  protected function afterAction() {
    if ($this->hasOutput) {
      $this->__call('initPath');
      if (empty($this->d['pageTitle']) and !empty($this->page['settings']['itemTitle']))
        $this->setPageTitle($this->page['settings']['itemTitle']);
      if (isset($this->d['itemUser'])) $this->pageUser = $this->d['itemUser'];
      elseif (isset($this->d['itemsUser'])) $this->pageUser = $this->d['itemsUser'];
    }
    parent::afterAction();
  }
  
  public function initPath() {
    $this->__call('initAuthorPath');
    $this->initItemPath();
  }

  protected function initAuthorPath() {
    // Если необходимо добавлять пользователя в путь к странице
    if ($this->page->getS('ownerMode') != 'author') return;
    if (!empty($this->d['itemUser'])) {
      $this->setPathData(Tt::getPath(1).'/u.'.$this->d['itemUser']['id'], 
        $this->d['itemUser']['login']);
    }
  }

  /**
   * Добавляет привилегии для текущего пользователя
   *
   */
  protected function setPrivAfterInit() {
    if (!$this->itemId) return;
    if (!($item = $this->setItemData())) return;
    if (Privileges::extendItemPriv($item, Auth::get('id'), $this->editTime)) {
      // Если включено премодерирование, ограничиваем данную привилегию до "editOnly"
      !empty($this->page['settings']['premoder']) ? $this->priv['editOnly'] = 1 : $this->priv['edit'] = 1;
    }
    $this->initAllowedActions();
  }
  
  protected function showFormOnDefault() {
    $this->oManager->oForm->setActionFieldValue('new');
    $this->d['form'] = $this->oManager->oForm->html();
  }
  
  public function action_list() {
    // @todo убрать этот кусок куда-нибудь в ф-ю
    // Вывод формы по умолчанию
    if (!empty($this->page['settings']['showFormOnDefault']))
      $this->showFormOnDefault();
    
    /////////////////////////////////////////////////////////////
    ////// "Необходимо присутствие фильтра по пользователю"
    ////// Актуально только для дефолтового экшена вывода записей
    /////////////////////////////////////////////////////////////
    // В каких случаях это необходимо?
    // Ответ: в случае записей пользователя
    if ($this->strictFilters and
       isset($this->page['settings']['userFilterRequired']) and
       $this->page['settings']['userFilterRequired'] and
       $this->params[$this->paramFilterN] !=
       'u') {
        // Если необходимо присутствие фильтра по пользователю, а он не задан
        $this->error404(
          'Необходимо присутствие фильтра по пользователю');
      return;
    }
    /*
    if ($this->params[$this->paramFilterN] and 
        !preg_match('/\d+/', $this->params[$this->paramFilterN]) and 
        !$this->params[$this->paramFilterN + 1]) {
      // Если 2-й параметр не число, а 3-й параметр не определен
      $this->error404('Не определено значение фильтра');
      return;
    }
    */
    /////////////////////////////////////////////////////////////////////
    

    // Если существуют несколько разделов этой структуры, мы можем перемещать
    //$this->d['canMove'] = count(db()->selectCol('SELECT id FROM pages WHERE id=?', $this->strName)) > 1;
    $this->d['tpl'] = 'default';
    $this->initListSlicesId();
    $this->initListTagPath();
    
    // Подписка на новые записи раздела
    $this->d['subscribedNewItems'] = 
      Notify_SubscribePages::subscribed($this->userId, 'items_new', 
        $this->page['id']);
  }

  /**
   * Определяет ID для всех слайсов находящихся в списке записей (перед ними и после)
   */
  protected function initListSlicesId() {
    if (empty($this->page['settings']['listSlicesType'])) {
      $this->d['listSlicesId'] = $this->page['id'];
      return;
    } elseif (preg_match('/tag_(\w+)/', $this->page['settings']['listSlicesType'], $m)) {
      if (!$this->tagsSelected) {
        $this->d['listSlicesId'] = $this->page['id'];
      } elseif (($tag = Arr::getValueByKey($this->tagsSelected, 'groupName', $m[1])) !== false) {
        $this->d['listSlicesId'] = $this->page['id'].'_'.$tag['id'];
        $this->d['listSlicesTitle'] = $tag['title'];
      }
    } elseif (preg_match('/v_(\w+)/', $this->page['settings']['listSlicesType'], $m)) {
      $this->d['listSlicesId'] = 
        Arr::get_value($this->oManager->oItems->cond->filters['filter'], 'key', $m[1], 'value');
    }
    if (empty($this->d['listSlicesId']))
      throw new NgnException("\$this->d['listSlicesId'] is empty");
  }

  ////////////////////////////////
  /////// Параметры для выборок
  ////////////////////////////////
  
  /**
   * Определяет если параметры включают экшн 'list'
   *
   * @return bool
   */
  protected function isListParams() {
    return (isset($this->params[$this->paramFilterN]) and preg_match(
      '/^t2|t|d|u|v|ne|mx$/', $this->params[$this->paramFilterN]));
  }
  

  //////////////////// Date ////////////////////////
  

  const DATE_Y = 1;

  const DATE_MY = 2;

  const DATE_DMY = 3;

  const DATE_RANGE = 4;

  private $dateType;

  private $year;

  private $month;

  private $day;

  private $datePeriod;

  //////////////////// Tags ///////////////////////
  private $tags;

  private $tagsTypes;

  public $tagsSelected = array();
  
  protected $filterParams = array();

  /**
   * Инициализирует параметры фильтров и определяет их для класса
   * записей $this->oManager->oItems
   */
  protected function initFilterByParams() {
    for ($i=$this->paramFilterN; $i<count($this->params); $i++) {
      $m = array();
      $isFilterParams = false;
      if (preg_match('/([a-z]+[0-9]?)\.(.+)/', $this->params[$i], $m)) {
        $isFilterParams = true;
        $method = 'setFilter'.ucfirst($m[1]);
        if ($m[1] == 'd') {
          $this->setFilterDate($m[2]);
        } elseif ($m[1] == 't2' or $m[1] == 't') {
          if (count($m) != 3) throw new NgnException('Params count must be 3');
          // Четко по тэгу "page/t2.tagGroup.tagId"
          list($tagName, $tagValue) = explode('.', $m[2]);
          $this->setFilterTags($tagValue, $tagName, ($m[1] == 't2'));
        } elseif ($m[1] == 'u') {
          // Четко по пользователю
          $this->setFilterUser($m[2]);
        } elseif ($m[1] == 'v') {
          // Четко по значению поля. Пример /asd.asd/v.title.Какой-то заголовок
          list($fieldName, $value) = explode('.', $m[2]);
          $value = $value == 'none' ? '' : $value;
          $this->oManager->oItems->addF($fieldName, urldecode($value));
        } elseif ($m[1] == 'ne') {
          // Not Empty
          $this->oManager->oItems->addNullFilter($m[1], false);
        } elseif (method_exists($this, $method)) {
          // Динамический метод
          $this->$method($m[2]);
        } else {
          $isFilterParams = false;
        }
      }
      if ($isFilterParams) {
        if ($m[1] == 'd')
          $this->d['filters'][$m[1]] = $m[2];
        else
          $this->d['filters'][$m[1]] = explode('.', $m[2]);
      }
    }
    $this->d['tagsSelected'] = $this->tagsSelected;
  }
  
  protected function initUserGroupFilter() {
    if (!$this->userGroup) return;
    $this->oManager->oItems->addF('userGroupId', $this->userGroup['id']);
  }
  
  protected $curUser;
  
  /**
   * Ф-я определения фильтра по пользователю для записей не 
   * имеющих параметра "mysite"
   *
   * @param   integer   ID пользователя
   */
  public function setFilterUser($userId) {
    $this->curUser = $this->d['itemsUser'] = DbModelCore::get('users', $userId);
    if (empty($this->d['itemsUser'])) {
      $this->error404('Пользователь не найден');
      return;
    }
    if ($this->page->getS('ownerMode') == 'author') {
      $this->setPageTitle($this->d['pageTitle'].' — '.$this->d['itemsUser']['login']);
      $this->setPathData(Tt::getPath(2), $this->d['itemsUser']['login']);
    }
    $this->oManager->oItems->addF('userId', $userId);
    $this->d['submenu'] = UserMenu::get(
      $this->d['itemsUser'],
      $this->d['page']['id'],
      $this->action
    );
  }
  
  protected $dateParam;

  private function setFilterDate($dateParam) {
    if (empty($this->page['settings']['dateField'])) {
      throw new NgnException('Filter use date. You must define "dateField" in page settings.');
    }
    $this->dateParam = $dateParam;
    
    // Парсим параметры даты
    // Четко по дате
    if (preg_match(
      '/(\d+).(\d+).(\d+)-(\d+).(\d+).(\d+)/', $dateParam, $m)) {
      // Период
      $this->datePeriod['from']['d'] = $m[1];
      $this->datePeriod['from']['m'] = $m[2];
      $this->datePeriod['from']['y'] = $m[3];
      $this->datePeriod['to']['d'] = $m[4];
      $this->datePeriod['to']['m'] = $m[5];
      $this->datePeriod['to']['y'] = $m[6];
      $this->dateType = self::DATE_RANGE;
    } else {
      // Конкретная дата
      $date = explode('.', $dateParam);
      if (count($date) == 3) {
        // Указан конкретный день
        $this->day = $date[0];
        $this->month = $date[1];
        $this->year = $date[2];
        $this->dateType = self::DATE_DMY;
      } elseif (count($date) == 2) {
        // Указан конкретный месяц
        $this->month = $date[0];
        $this->year = $date[1];
        $this->dateType = self::DATE_MY;
      } elseif (count($date) == 1) {
        // Указан конкретный год
        $this->year = $date[0];
        $this->dateType = self::DATE_Y;
      }
    }
    // Устанавливаем параметры для фильтров
    if ($this->dateType == self::DATE_RANGE) {
      $this->oManager->oItems->cond->addRangeFilter(
        $this->page['settings']['dateField'], 
        $this->datePeriod['from']['y'] . '-' . $this->datePeriod['from']['m'] . '-' . $this->datePeriod['from']['d'], 
        $this->datePeriod['to']['y'] . '-' . $this->datePeriod['to']['m'] . '-' . $this->datePeriod['to']['d']
      );
    } elseif ($this->dateType == self::DATE_DMY) {
      // Заголовок типа "Литературные новости (11 августа 2009)"
      $m = Config::getVar('ru-months2');
      $this->setPageTitle($this->d['pageTitle'].' ('.
        $this->day.' '.mb_strtolower($m[(int)$this->month], CHARSET).
        ' '.$this->year.')');
      // ------------  
      $this->oManager->oItems->addF($this->page['settings']['dateField'], 
        sprintf('%04d-%02d-%02d', $this->year, $this->month, $this->day), 
        'DATE');
    } elseif ($this->dateType == self::DATE_MY) {
      // Заголовок типа "Литературные новости (Август 2009)"
      $m = Config::getVar('ru-months');
      $this->setPageTitle($this->d['pageTitle'].' ('.$m[(int)$this->month].' '.$this->year.')');
      // ------------  
      $this->oManager->oItems->addF($this->page['settings']['dateField'], 
        (int)$this->month, 'MONTH');
      $this->oManager->oItems->addF($this->page['settings']['dateField'], 
        (int)$this->year, 'YEAR');
    } elseif ($this->dateType == self::DATE_Y) {
      $this->oManager->oItems->addF($this->page['settings']['dateField'], 
        (int)$this->year, 'YEAR');
    }
  }

  protected function setFilterTags($tagsParam, $tagField, $byId = true) {
    /*
    // Парсим параметры тэгов
    if (is_array($tagsParam)) {
      // Тэги указаны вместе с типами
      throw new NgnException('действие не реализовано');
      // ..........+..........
    } else {
      // Тэги указаны без типов. Выборка не учитывает типа
      // ..........+..........
      $ids = array();
      if (strstr($tagsParam, ',')) {
        // Условие выборки "или"
        $tagNames = explode(',', $tagsParam);
        $oTags = DdTags::get($this->strName, $tagField);
        if ($oTags->getGroup()->isTree())
          throw new NgnException("Getting tags by name supportes only flat tags. '$tagField' is tree type tag.");
        foreach ($tagNames as $name) {
          foreach ($oTags->getByName($name) as $tag) {
            $this->tagsSelected[] = $tag;
          }
          foreach (DdTagsItems::getIdsByName($this->strName, $tagField, $name) as $id)
            if (!in_array($id, $ids)) $ids[] = $id;
        }
        if ($ids) $this->oManager->oItems->addF('id', $ids);
      } elseif (strstr($tagsParam, '+')) {
        // Условие выборки "и"
        $tagNames = explode('+', $tagsParam);
        foreach ($tagNames as $name)
          $ids[] = DdTags::getItemIds($name, $tagField, 
            $this->page['id']);
        for ($i = 1; $i < count($ids); $i++)
          $intersectIds = array_intersect($ids[$i - 1], $ids[$i]);
        if ($intersectIds)
          $this->oManager->oItems->addF('id', $intersectIds);
        // Определяем заголовок
        foreach ($tagNames as $name) {
          if (($tag = DdTags::getTag($name, $tagField, $this->page['id']))) {
            $titles[] = $tag['title'];
            $this->tagsSelected[] = $tag;
          }
        }
        if ($titles)
          $this->setPageTitle(implode(' + ', $titles));
        $this->d['tagsSplitter'] = '+';
      } else {
        // ------------------------------------
        // Фильтр по одному тэгу
        // ------------------------------------
        DdTagsItems::$getNonActive = isset($this->priv['edit']);
        $oTags = DdTags::get($this->strName, $tagField);
        if (is_numeric($tagsParam)) {
          if ($oTags->getGroup()->isTree()) {
            $tag = DdTags::getById($tagsParam);
          } else {
            $tag = DdTags::getById($tagsParam);
          }
          if (!empty($tag)) {
            $this->tagsSelected[] = $tag;
            $itemIds = DdTagsItems::getIdsByTagId($this->strName, $tagField, $tagsParam);
          }
        } else {
          if ($oTags->getGroup()->isTree())
            throw new NgnException("Getting tags by name supportes only flat tags. '$tagField' is tree type tag.");
          if (($tag = $oTags->getByName($tagsParam))) { 
            $this->tagsSelected[] = $tag;
            $itemIds = DdTagsItems::getIdsByName($this->strName, $tagField, $tagsParam);
          }
        }
        if (empty($itemIds)) {
          // Если нет тэгов, делаем значение фильтра таким, что бы выборка была нулевая
          $itemIds = -1;
        }
        $this->oManager->oItems->addF('id', $itemIds);
      }
    }
    */
    $oTags = DdTags::get($this->strName, $tagField);
    if (!$byId and $oTags->getGroup()->isTree())
      throw new NgnException("Getting tags by name supportes only flat tags. '$tagField' is tree type tag.");
    $tag = $byId ?
      DbModelCore::get('tags', $tagsParam) :
      DbModelCore::get('tags', $tagsParam, 'name');
    if ($tag === false) throw new NgnException('There is no such tag: '.$tagsParam);
    $this->tagsSelected[] = $tag->r;
    $itemIds = DdTagsItems::getIdsByTagId($this->strName, $tagField, $tag['id']);
    if (empty($itemIds))
      // Если нет тэгов, делаем значение фильтра таким, что бы выборка была нулевая
      $itemIds = -1;
    $this->oManager->oItems->addF('id', $itemIds);
  }

  /**
   * Добавляет данные 'items' и 'pNums' в массив $this->d
   */
  protected function initItems() {
    $this->initFilterByParams();
    $this->initUserGroupFilter();
    $this->oManager->oItems->setPagination(true);
    $this->oManager->oItems->cond->setOrder($this->order);
    $hookPaths = SiteHook::getPaths('dd/initItems', $this->page['module']);
    if ($this->itemsCacheEnabled) {
      $cacher = new DdItemsCacher($this->oManager->oItems);
      if ($hookPaths) foreach ($hookPaths as $path) include $path;
      $this->d['itemsHtml'] = $cacher->initHtml()->html();
    } else {
      if ($hookPaths) foreach ($hookPaths as $path) include $path;
      $this->d['items'] = $this->oManager->oItems->getItems();
    }
    $this->d['pagination'] = $this->oManager->oItems->getPagination();
  }
  
  public $itemsCacheEnabled = true;
  
  /**
   * Добавляет данные 'items' без разбивки на страницы
   */
  public function setItemsOnItem() {
    if (!empty($this->page['settings']['tagField'])) {
      $oTags = DdTags::get($this->strName, $this->page['settings']['tagField']);
      if (
      !$oTags->getGroup()->isMulti() and
      !empty($this->tagsSelected[0])
      ) {
        $this->oManager->oItems->addF(
          'id',
          $oTags->getGroup()->isTree() ?
            DdTagsItems::getIdsByName($this->strName, $this->page['settings']['tagField'],
              $this->tagsSelected[0]['id']) :
            DdTagsItems::getIdsByName($this->strName, $this->page['settings']['tagField'],
              $this->tagsSelected[0]['name'])
        );
      }
    }
    $this->oManager->oItems->cond->setOrder($this->order);
    if (!empty($this->page['settings']['setItemsOnItemLimit']))
      $this->oManager->oItems->cond->setLimit($this->page['settings']['setItemsOnItemLimit']);
    $this->d['items'] = $this->oManager->oItems->getItems();
  }

  protected function setItemsCanRate(array &$items) {
    if (!count($items)) return;
    if (!$oVoter = RatingVoter::getVoterDd(new VoteObjectDd($this->strName)))
      return;
    $votedIds = $oVoter->getVotedIds(array_keys($items));
    foreach ($items as $id => &$v)
      if (!in_array($id, $votedIds))
        $v['canRate'] = true;
  }
  
  protected function setItemCanRate(array &$item) {
    if (!($oVoter = RatingVoter::getVoterDd(new VoteObjectDd($this->strName))))
      return;
    if (!$oVoter->getVotedIds(array($item['id'])))
      $item['canRate'] = true;
  }
  

  ///////////// Begin Move Actions /////////////////

  public function action_moveForm() {
    $this->d['tpl'] = 'move';
    $this->d['postAction'] = 'moveForm2';
  }

  public function action_moveForm2() {
    $this->action_moveForm();
    $this->setMoveStep2Data();
    $this->d['postAction'] = 'move';
  }

  public function action_moveGroupForm() {
    $this->d['tpl'] = 'move';
    $this->d['itemIds'] = $this->oReq->rq('itemIds');
    $this->d['postAction'] = 'moveGroupForm2';
  }

  public function action_ajax_deleteGroup() {
    foreach ($this->oReq->rq('itemIds') as $itemId)
      $this->oManager->delete($itemId);
  }

  public function action_moveGroupForm2() {
    $this->action_moveGroupForm();
    $this->setMoveStep2Data();
    $this->d['postAction'] = 'moveGroup';
  }

  /*
  protected function setMoveStep2Data() {
    $this->d['toPageId'] = $this->oReq->rq('pageId');
    $this->d['toPageData'] = O::get('Pages')->getNode($this->d['toPageId']);
    $o = new DdStrConverter($this->page['id'], $this->d['toPageId']);
    $this->d['conformance'] = $o->getTitledConformance();
  }
  */
  
  public function action_changeAuthorGroupForm() {
    $this->d['tpl'] = 'changeAuthor';
    $this->d['itemIds'] = $this->oReq->rq('itemIds');
    $this->d['postAction'] = 'changeAuthorGroup';
  }
  
  public function action_changeAuthorGroup() {
    db()->query("UPDATE dd_i_{$this->strName} SET userId=?d WHERE id IN (?a)",
      $this->oReq->rq('userId'), $this->oReq->rq('itemIds'));
    $this->redirect();
  }
  

  ///////////// End Move Actions /////////////////

  function extendItemData() {}

  public function action_move() {
    $this->hasOutput = false;
    if (empty($this->itemId))
      throw new NgnException('$this->itemId is empty');
    $this->oManager->oItems->move(
      array($this->itemId), $this->oReq->rq('pageId'));
    $this->moveRedirect($this->oReq->rq('pageId'));
  }

  public function action_moveGroup() {
    $this->hasOutput = false;
    if (!isset($_POST['itemIds']) or !count($_POST['itemIds']))
      throw new NgnException("\$_POST['itemIds'] is not defined or not an array");
    if (empty($this->oReq->r['pageId']))
      throw new NgnException("\$this->oReq->r['pageId'] is empty");
    $this->oManager->oItems->move(
      $_POST['itemIds'], $this->oReq->r['pageId']);
    $this->moveRedirect($this->oReq->r['pageId']);
  }

  protected $moveRedirectFunc;

  protected function setMoveRedirect($code) {
    $this->moveRedirectFunc = create_function(null, $code);
  }
  
  protected function moveRedirect($pageId) {
    $this->completeRedirect();
  }

  protected function initItemPath() {
    if (!$this->itemData)
      return; // Если не определены данные конкретной записи, то ничего делать и не нужно
    if (!empty($this->page['settings']['tagField'])) {
      $oTags = DdTags::get($this->strName, $this->page['settings']['tagField']);
      $this->setTagTreePath($oTags, $this->itemData[$this->page['settings']['tagField']]);
    }
    if (!empty($this->page['settings']['titleField']) and 
        isset($this->itemData[$this->page['settings']['titleField']])) {
      $this->setCurrentPathData($this->itemData[$this->page['settings']['titleField']]);
      $this->setPageTitle($this->itemData[$this->page['settings']['titleField']]);
    } else {
      $title = !empty($this->itemData['title']) ? $this->itemData['title'] : '...';
      $this->setPathData(Tt::getPath(1).'/'.$this->itemData['id'], $title);
      $this->setPageTitle($title);
    }
  }

  protected function initItemTagsData() {
    foreach ($this->d['fields'] as $k => $fld) {
      if (strstr($fld['type'], 'tags')) {
        $flds[] = $k;
      }
    }
    if (!isset($flds)) return;
    foreach ($flds as $fld)
      $this->d['tags'][$fld] = DdTags::get($this->strName, $fld)->getData();
    // Действия только для случаев, если в настройках раздела выбрано поле 'tagField' 
    if (!empty($this->page['settings']['tagField']) and
    !empty($this->itemData[$this->page['settings']['tagField']])) {
      // Выбранные тэги
      $this->tagsSelected[] = 
        isset($this->itemData[$this->page['settings']['tagField']][0]) ?
        $this->itemData[$this->page['settings']['tagField']][0] :
        $this->itemData[$this->page['settings']['tagField']];
      $this->d['tagsSelected'] = $this->tagsSelected;
    }
  }
  
  /**
   * Добавляет ссылку на тэг в хлебные крошки
   */
  protected function initListTagPath() {
    if (empty($this->tagsSelected)) return;
    // Древовидный путь может строиться только по определенному в настройках тэгу
    if (isset($this->tagsSelected[0])) {
      $oTags = DdTags::get($this->strName, $this->tagsSelected[0]['groupName']);
      if ($oTags->getGroup()->isTree() and $this->setTagTreePath($oTags,
        array($oTags->getBranchFromRoot($this->tagsSelected[0]['id']))
      )) return;
    }
    Misc::checkEmpty($this->tagsSelected[0]['title']);
    $this->setPageTitle($this->tagsSelected[0]['title']);
    $this->setPathData(Tt::getPath(), $this->tagsSelected[0]['title']);
  }
  
  protected function setTagTreePath(DdTagsTagsBase $oTags, array $branchFromRoot) {
    // Только для древовидного не мульти-типа при наличии
    if (!$oTags->getGroup()->isTree() or $oTags->getGroup()->isMulti()) return false;
    $tagsFlat = DdTagsHtml::treeToList($branchFromRoot);
    foreach ($tagsFlat as $v) {
      $this->setPathData(Tt::getPath(1).'/t2.'.
        $oTags->getGroup()->name.'.'.$v['id'], $v['title']);
    }
    // Определяем заголовком страницы последний тэг дерева
    $this->setPageTitle($tagsFlat[count($tagsFlat)-1]['title']);
    return true;
  }

  public function action_showItem() {
    $this->d['tpl'] = 'item';    
    if (!$this->setItemData()) {
      // Если Записи с таким ID не существует
      $this->error404('Записи с таким ID не существует');
      return;
    }
    if ($this->itemData['pageId'] != $this->page['id']) {
      // Если Запись принадлежит не этому разделу
      $this->error404('Запись принадлежит не этому разделу');
      return;
    }
    if (!$this->itemData['active'] and !$this->allowAction('edit')) {
      // Если Запись не активна и нет прав на её редактирование
      $this->error404('Запись не активна или нет прав на её редактирование');
      return;
    }
    $this->extendItemData();
    $this->initItemTagsData();
    $this->setItemCanRate($this->itemData);
    $this->d['item'] = $this->itemData;
    if (!empty($this->page['settings']['setItemsOnItem'])) $this->setItemsOnItem();
    $this->click(); // Клик
    // Комменты
    if (isset($this->subControllers['comments'])) {
      $this->subControllers['comments']->action_default();
      $this->d['showCommentsAfterItem'] = true;
    }
    $this->itemAuthorMode();
    $this->initListTagPath();
    // Получаем существующие месяца
    /*
    if (!empty($this->page['settings']['dateField'])) {
      if (! isset($this->itemData[$this->page['settings']['dateField']]))
        throw new NgnException('No dateField');
      $this->d['year'] = date('Y', 
        $this->itemData[$this->page['settings']['dateField'] . '_tStamp']);
      $this->d['month'] = date('n', 
        $this->itemData[$this->page['settings']['dateField'] . '_tStamp']);
      $this->d['months'] = $this->oManager->oItems->getMonths(
        $this->page['settings']['dateField']);
    }
    */
  }
  
  protected function itemAuthorMode() {
    if ($this->page->getS('ownerMode') != 'author') return;
    $this->d['itemUser'] = DbModelCore::get('users', $this->itemData['userId']);
    $this->d['submenu'] = UserMenu::get(
      $this->d['itemUser'],
      $this->page['id'],
      $this->action
    );
  }

  public function action_edit() {
    if (!parent::action_edit()) return false;
    $this->setCurrentPathData($this->itemData['title']);
    return true;
  }

  public function action_activate() {
    $this->oManager->oItems->activate($this->oReq->rq('itemId'));
    $this->redirect('referer');
  }

  public function action_deactivate() {
    $this->oManager->oItems->deactivate($this->oReq->rq('itemId'));
    $this->redirect('referer');
  }

  /**
   * Публикация записи модератором
   */
  public function action_publish() {
    $this->oManager->oItems->activate($this->oReq->rq('itemId'));
    $this->oManager->oItems->updatePublishDate($this->oReq->rq('itemId'));
    $this->completeRedirect();
    /*
    $item = $this->oManager->oItems->getItem($this->itemId);
    Notify_SenderRobot::send(
      $item['authorId'],
      'Ваша запись была опубликована',
      'Посмотрите её уже скорей: <a href="'.Tt::getPath().'">look</a>'
    );
    $this->completeRedirect();
    */
  }

  public function action_ajax_activate() {
    $this->oManager->oItems->activate($this->oReq->rq('itemId'));
  }

  public function action_ajax_deactivate() {
    $this->oManager->oItems->deactivate($this->oReq->rq('itemId'));
  }

  public function action_up() {
    $this->oManager->oItems->shiftUp($this->oReq->rq('itemId'));
    $this->redirect('referer');
  }

  public function action_down() {
    $this->oManager->oItems->shiftDown($this->oReq->rq('itemId'));
    $this->redirect('referer');
  }

  public function action_ajax_reorder() {
    $this->oManager->oItems->reorderItems($this->oReq->rq('ids'));
  }

  public function action_getFile() {
    $fn = $this->oReq->rq('fn');
    $strData = DdStructure::getData($this->oManager->oItems->strName);
    if ($strData['locked'] and !$this->priv['view']) {
      // Раздел имеет структуру с ограниченным доступом, а вы не имеете прав на её просмотр
      $this->error404(
        'Раздел имеет структуру с ограниченным доступом, а вы не имеете прав на просмотр');
      return;
    }
    $itemId = $this->itemId ? $this->itemId : (int)$this->oReq->r['itemId'];
    if (!($item = $this->oManager->oItems->getItem($itemId))) {
      // Нет записи с таким ID
      $this->error404('Нет записи с таким ID');
      return;
    }
    if (!isset($item[$fn])) {
      throw new NgnException("Field '$fn' does not exisst");
    }
    
    if (isset($item[$fn.'_dl'])) {
      $d = array(
        $fn.'_dl' => ++$item[$fn.'_dl']
      );
      $this->oManager->oItems->update($itemId, $d);
    }
    
    $filename = basename($item[$fn]);
    list($name, $ext) = explode('.', $filename);
    $filename = $name.'-'.$item['id'].'.'.$ext;
    header('Content-type: application/download');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    readfile(WEBROOT_PATH.'/'.$item[$fn]);
    $this->hasOutput = false;
  }

  public function action_updateRating() {
    Voting::vote($this->page['id'], $this->itemId, 'rating', Auth::get('id'), 
      $this->oManager->oItems);
    $this->redirect('referer');
  }

  /////////// Tags /////////

  /**
   * Добавляет к шаблонным данным данные тэгов для текущего раздела
   */
  protected function initTagsTplCommonData() {
    foreach ($this->oManager->oForm->oFields->getTagFields() as $name => $v) {
      $oTags = DdTags::get($this->strName, $name);
      $this->d['tags'][$name] = $oTags->getGroup()->isTree() ?
        $oTags->getTree() : $oTags->getTags();
    }
    if ($this->tagsSelected) { // Если определены выбранные тэги
      $selNames = Arr::get($this->tagsSelected, 'name'); // Получаем имена этих тэгов
      foreach ($this->d['tags'] as &$tag) { // Проверяем все на "выбранность"
        foreach ($tag as &$v) {
          if (in_array($v['name'], $selNames)) {
            $v['selected'] = true;
          }
        }
      }
    }
  }

  public function action_tags() {
    if (empty($this->tags))
      throw new NgnException('$this->tags is empty');
    $ids = array();
    foreach ($this->tags as $tag) {
      $ids += DdTags::getItemIds($tag, $this->tagType, $this->page['id']);
    }
    $this->oManager->oItems->addF('id', $ids);
    $this->oManager->oItems->isPages = true;
    $this->d['items'] = $this->oManager->oItems->getItems();
    $this->d['pagination']['pNums'] = $this->oManager->oItems->pNums;
    $this->setCurrentPathData($this->d['tag']['title']);
    $this->setPageTitle($this->d['tag']['title']);
  }

  //////////// Calendar //////////////

  protected function initCalendarData() {
    return;
    if (!isset($this->page['settings']['dateField']) or !$this->page['settings']['dateField'])
      return;
    $this->oManager->oItems->dateField = $this->page['settings']['dateField'];
    $calendar = new CalendarItems($this->d['page']['path'], $this->oManager->oItems);
    if ($this->month) {
      $month = $this->month;
      $year = $this->year;
    } else {
      $month = date('n');
      $year = date('Y');
    }
    $this->d['calendar'] = $this->getBesideMonths($month, $year);
    $this->d['calendar']['table'] = $calendar->getMonthView($month, $year);
    // Получаем существующие месяца
    if ($this->page['settings']['dateField']) {
      $this->d['months'] = $this->oManager->oItems->getMonths(
        $this->page['settings']['dateField']);
    }
  }
  
  public function action_ajax_calendar() {
    $this->ajaxOutput = Tt::getTpl('common/calendarInner', $this->d['calendar']);
  }

  protected function getBesideMonths($m, $y) {
    $months = Config::getVar('ru-months2');
    $prevMonthTime = mktime(0, 0, 0, $m - 1, 1, $y);
    $nextMonthTime = mktime(0, 0, 0, $m + 1, 1, $y);
    $r = array();
    $r['prevMonth'] = $months[date('n', $prevMonthTime)];
    $r['nextMonth'] = $months[date('n', $nextMonthTime)];
    $r['prevMonthDate'] = date('m.Y', $prevMonthTime);
    $r['nextMonthDate'] = date('m.Y', $nextMonthTime);
    return $r;
  }

  public function action_ajax_rate() {
    RatingVoter::getVoterDd(new VoteObjectDd($this->strName))->
      vote($this->oReq->rq('itemId'), $this->oReq->rq('n'));
  }

  public function action_rss() {
    $limit = !empty($this->page['settings']['rssN']) ? $this->page['settings']['rssN'] : 20;
    $header['title'] = SITE_TITLE.': '.$this->page['title'];
    $header['description'] = $this->page['title'].' '.$_SERVER['SERVER_NAME'];
    $header['link'] = "http://".$_SERVER['SERVER_NAME'].Tt::getPath();
    $this->oManager->oItems->setN($this->page['settings']['rssN'] ? $this->page['settings']['rssN'] : 20);
    $this->oManager->oItems->cond->setOrder('dateCreate DESC');
    $n = 0;
    foreach ($this->oManager->oItems->getItems() as $v) {
      $n++;
      $items[] = array(
        'title' => $v[$this->page['settings']['rssTitleField']] ?
          str_replace('<', '{', str_replace('>', '}', $v[$this->page['settings']['rssTitleField']])) :
          '{нет заголовка}', 
        'description' => !empty($this->page['settings']['rssDescrField']) ?
          $v[$this->page['settings']['rssDescrField']] : '', 
        'link' => 'http://'.SITE_DOMAIN.'/'.$this->page['path'].'/'.$v['id'], 
        'author' => $v['authorLogin'], 
        'guid' => isset($v['link']) ? $v['link'] : '', 
        'pubDate' => date('r', $v['dateCreate_tStamp']), 
        'category' => $this->page['title']
      );
      if ($limit == $n) break;
    }
    header('Content-type: text/xml; charset='.CHARSET);
    $this->hasOutput = false;
    print 
      O::get('Rss', 'default')->getXml(
        array(
          'header' => $header, 
          'items' => $items
        ));
  }

  public function action_authors() {
    /* @var $oDdItems DdItems */
    $oDdItems = $this->oManager->oItems;
    $oDdItems->cond->setOrder('u.dateCreate DESC');
    $this->d['items'] = $oDdItems->getAuthors();
    $this->d['tpl'] = 'authors';
    $this->setPageTitle($this->page['title'].' — авторы');
    $this->setPathData(Tt::getPath().'?a=authors', 'Авторы');
  }
  
  protected $deletedIds = array();
  
  public function deletePage() {
    parent::deletePage();
    if (!isset($this->oManager))
      $this->initItemsManager();
    $this->oManager->deleteAll();
  }
  
  
  // ------------------------- mysite -------------------------------
  
  protected $mysiteOwner;
  
  /**
   * Инициализация функционала для работы контроллера под сабдоменом
   */
  protected function initMysite() {
    if (!empty($this->page['settings']['mysite']) and empty($this->options['subdomain']))
      throw new NgnException('Controller must work only with subdomain');
    if (!isset($this->options['subdomain'])) return;
    if (!($this->mysiteOwner = DbModelCore::get('users', $this->options['subdomain'], 'name')))
      throw new NgnException('User with name "'.$this->options['subdomain'].'" does not exists');

    // Привелегии
    if ($this->mysiteOwner == Auth::get('id')) {
      $this->priv['edit'] = 1;
      $this->priv['create'] = 1;
      $this->priv['sub_create'] = 1;
      $this->priv['sub_edit'] = 1;
      
      $this->initAllowedActions();
    }
    
    // Хлебные крошки
    $this->d['pathData'][0] = array(
      'title' => $this->mysiteOwner['login'],
      'link' => Tt::getPath(0)
    );    
    
    // Данные пользователя
    $this->d['user'] = $this->mysiteOwner;
    $this->d['user'] += UsersCore::getImageData($this->mysiteOwner['id']);
    
    // Фильтр
    $this->oManager->oItems->addF('userId', $this->mysiteOwner['id']);
    
    // Другие разделы пользователя
    $this->d['submenu'] = UserMenu::get(
      $this->mysiteOwner,
      $this->d['page']['id'],
      $this->action
    );
  }  

}