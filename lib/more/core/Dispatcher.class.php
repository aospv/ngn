<?php

define('DISP_TYPE_TPL_BY_PATH', 1);
define('DISP_TYPE_DB_TREE_BY_PATH', 2);

SFLM::$debug = DEBUG_STATIC_FILES;
SFLM::$forceCache = FORCE_STATIC_FILES_CACHE;

/**
 * 
 * @todo Переименовать класс в Req
 * 
 * Диспетчер - это то место, где определяется в какой контроллер дальше 
 * будет переходить управление.
 * 
 * Это может зависеть от адреса запроса, либо дополнительной информации, 
 * полученной из БД.
 * 
 * Варианты управления диспетчером должы определяться в наследуеммых классах
 * 
 * Тут же общий функционал
 * 
 * ....................
 * 
 * 
 * Пока пробуем сделать тут всё, что нужно по типа "Path Routing Templates"
 * Потом будем делать тип "Simple DB Trees"
 * 
 * ....................
 * 
 * @todo На первом этапе проработать логику подключение контроллеров, 
 *       не касаясь БД
 *
 */
abstract class Dispatcher extends Options {
  
  /**
   * Тип подключения контроллеров
   *
   * @var integer
   */

  /**
   * Необходимо ли подключение к БД
   *
   * @var bool
   */
  protected $isDB = true;
  
  /**
   * @var CtrlCommon
   */
  public $oController;
  
  /**
   * @var Req
   */
  protected $oReq;
  
  public function __construct(array $options = array()) {
    $this->setOptions($options);
    $this->oReq = isset($this->options['oReq']) ? $this->options['oReq'] : O::get('Req');
    $this->init();
  }
  
  protected function init() {
    $this->headers();
    if ($this->isDB) {
      $this->session();
      $this->auth();
    }
  }
  
  protected function need2patch() {
    if (O::get('DbPatcher')->need2patch() and
        !strstr($_SERVER['REQUEST_URI'], '/c/dbPatcher')) {
      redirect(Tt::getPath(0).'/c/dbPatcher');
    }
    if (O::get('FilePatcher')->need2patch() and
        !strstr($_SERVER['REQUEST_URI'], '/c/filePatcher')) {
      redirect(Tt::getPath(0).'/c/filePatcher');
    }
  }
  
  public function dispatch() {
    $this->initController();
    if (!is_object($this->oController))
      throw new NgnException('Controller not initialized');
    // В этом месте, после диспатчинга контроллера, может произойти его подмена
    // т.е. контроллер $this->oController заменит себя другим контроллером или,
    // другими словами, передаст управление
    $this->oController->dispatch();
    return $this;
  }
  
  protected function headers() {
    if (!empty($this->options['disableHeaders'])) return;
    header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    header('Pragma: no-cache');
  }
  
  /**
   * Авторизация
   */
  protected function auth() {
    if (isset($_GET['logout'])) {
      Auth::logout();
      header('Location: '.Tt::getUrlDeletedParams($_SERVER['REQUEST_URI'], array('logout')));
    } elseif (isset($_GET['clear'])) {
      Auth::clear();
      //header('Location: '.Tt::getUrlDeletedParams($_SERVER['REQUEST_URI'], array('clear')));
    } else {
      // Auth::$doNotSavePass = $_REQUEST
      Auth::setAuth();
    }
  }
  
  protected function session() {
    if (isset($_COOKIE['myComputer']) and $_COOKIE['myComputer'] != 2) Session::$expires = 0;
    if (isset($_REQUEST['sessionId'])) $_COOKIE[session_name()] = $_REQUEST['sessionId'];
    if (empty($this->options['disableHeaders'])) Session::init();
  }
  
  /**
   * Здесь должен определяться контроллер!
   * $this->oController
   */
  abstract protected function initController();
  
  protected function afterOutput() {}
  
  public function getOutput() {
    if (!$this->oController) throw new NgnException('Controller not defined');
    Err::noticeSwitch(false);
    $html = $this->oController->getOutput();
    $this->afterOutput();
    return $html;
  }
  
}

