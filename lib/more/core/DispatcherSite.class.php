<?php

class DispatcherSite extends Dispatcher {

  public $page;

  public $oControllersManager;
  
  protected function init() {
    $this->need2patch();
    parent::init();
  }

  public function dispatch() {
    $this->checkAuthMode();
    $this->initPage();
    $this->initPageHtmlCache();
    if (!$this->cacheHtml) parent::dispatch();
    return $this;
  }

  private $showAuthPage = false;
  
  /**
   * Функция проверяет текущий авторизационный режим. Если authMode = 'denied'
   */
  protected function checkAuthMode() {
    if (ACCESS_MODE == 'all') return;
    if (!Auth::get('id')) $this->showAuthPage = true;
  }

  protected $cacheEnable = false;
  protected $cacheHtml;
  protected $cacheKey;
  
  protected function initPageHtmlCache() {
    if (!$this->cacheEnable) return;
    $this->cacheKey =
      str_replace('.', '_',
      str_replace('-', '_',
      'htmlPage'.implode('', $this->oReq->params)));
    $this->cache = NgnCache::c();
    $this->cacheHtml = $this->cache->load($this->cacheKey);
  }

  protected function initController() {
    if ($this->showAuthPage) {
      $this->oController = new CtrlPageAuth($this, O::get('Req'));
      return;
    }
    if ($this->virtualCtrl !== false) {
      $this->oController = PageControllersCore::getVirtualCtrl($this->virtualCtrl, $this);
      return;
    }
    // Далее идёт "порода" CtrlPage предков
    // Для контроллеров серии "Site" метод "setPageData" здесь должен быть 
    // обязательно выполнен
    if (empty($this->page) or !$this->page['active']) {
      $this->oController = new Ctrl404site($this);
      return;
      // -----------------------
    } elseif ($this->page['link']) {
      header('Location: '.$this->page['link']);
      die();
    } elseif (!empty($this->page['controller'])) {
      $this->oController = PageControllersCore::getController($this, $this->page);
    }
    if (!isset($this->oController)) {
      $this->oController = new CtrlDefault($this);
      $this->oController->setPage($this->page);
    }
    R::set('currentPageId', $this->page['id']);
    R::set('breadcrumbsPageIds', empty($this->page['pathData']) ?
      array() : Arr::get($this->page['pathData'], 'id'));
  }
  
  public function getOutput() {
    // PDA-версия
   //..if (($sd = die2(O::get('Req')->detectSubdomain)) !== false and $sd == 'pda') {
    //$this->oController->d['mainTpl'] = 'pda';
    //}
    
    if (!$this->cacheEnable) {
      $html = parent::getOutput();
    } elseif (!($html = $this->cache->load($this->cacheKey))) {
      $html = parent::getOutput();
      $this->cache->save($html, $this->cacheKey);
    }
    //$html = Html::baseDomainLinks($html);
    /*
    if ($this->oController->userGroup) {
      $html = preg_replace_callback(
        '/<\!-- Page Layout Begin -->(.*)<\!-- Page Layout End -->/sm',
        function($m) {
          return preg_replace(
            '/a href="\/*([^"])/',
            'a href="'.O::get('SiteRequest')->getAbsBase().'/$1',
            $m[1]
          );
        },
        $html
      );
    }
    */
    //$this->afterRender($html);
    return $html;
  }
  
  protected function afterRender(&$html) {
  }
  
  protected $virtualCtrl = false;
  
  protected function initPage() {
    if (isset($this->oReq->params[0]) and is_numeric($this->oReq->params[0]) and
    ($this->page = DbModelCore::get('pages', $this->oReq->params[0])) !== false) {
      $this->pathRedirect();
    } else {
      if (!isset($this->oReq->params[0]) or $this->oReq->params[0] == 'index.php') {
        if (($this->page = DbModelPages::getHomepage()) === false)
          throw new NgnException('Homepage not found');
      } else {
        $routes = Config::getVar('routes');
        if (isset($routes[$this->oReq->params[0]])) $cntl = $routes[$this->oReq->params[0]];
        else $cntl = $this->oReq->params[0];
        if (PageControllersCore::virtualCtrlExists($cntl)) {
          $this->virtualCtrl = $cntl;
          $this->page = PageControllersCore::getVirtualCtrlPageModel($cntl);
        } elseif (($this->page = 
        DbModelCore::get('pages', $cntl, 'path')) !== false) {
          // by path
        } elseif (($this->page = 
        DbModelCore::get('pages', $cntl, 'name')) !== false) {
          // by name
        }
      }
    }
  }

  protected function pathRedirect() {
    if (empty($this->page['path'])) return;
    $pathParams2 = (count($this->oReq->params) > 1) ? '/' . implode('/', 
      array_splice($this->oReq->params, 1, count($this->oReq->params))) : '';
    header(
      'Location: /'.$this->page['path'].$pathParams2.
        ($_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : ''));
  }

  protected function error404() {
    header('HTTP/1.0 404 Not Found');
  }
  
  protected function afterOutput() {
    //db()->query(
    //  'INSERT INTO pages_log SET dateCreate=?, pageId=?d, title=?, url=?, processTime=?,memory=?, userId=?d, info=?',
    //  dbCurTime(), $this->page['id'], $this->page['title'], $_SERVER['REQUEST_URI'],
    //  getProcessTime(), memory_get_usage(), Auth::get('id'), serialize(Misc::getHttpClientInfo()));
  }
  
  protected function auth() {
    parent::auth();
    // Редирект на определенный раздел сразу после авторизации
    /*
    if (
    Auth::$postAuth and
    Config::getVarVar('userReg', 'redirectToFirstPage') and 
    ($pageIds = Config::getVarVar('userReg', 'pageIds'))
    ) {
      $path = db()->selectCell('SELECT path FROM pages WHERE id=?d',
        Arr::first(Arr::explodeCommas($pageIds)));
      if (!$path) throw new NgnException('Redirecting page does not exists');
       redirect($path);
    }
    */
  }

}
