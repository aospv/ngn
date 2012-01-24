<?php

Lang::load('admin');

class DispatcherAdmin extends Dispatcher {
  
  /**
   * Свойства текущего раздела администрирования
   *
   * @var array
   */
  public $prop;
  
  /**
   * Текущий модуль администрирования
   *
   * @var strgin
   */
  protected $module;
  
  /**
   * Текущий каталог модуля администрирования
   *
   * @var strgin
   */
  protected $moduleSubfolder;
  
  protected $allowedAdminModules;
  
  protected function init() {
    $this->need2patch();
    parent::init();
  }

  protected function initController() {
    if (!O::get('Req')->params[0]) {
      redirect('admin');
    }
    if (Auth::get('id') and isset($this->oReq->params[1])) {
      $this->module = $this->oReq->params[1];
      $this->moduleSubfolder = '/'.$this->module;
    } else {
      $this->module = 'default';
      $this->moduleSubfolder = '';
    }
    $this->allowedAdminModules = AdminModule::getAllowedModules();
    $this->_initController();
    // --- $this->prop = AdminModule::getProperties($this->module);
  }
  
  protected function _initController() {
    if ($this->oReq->params[0] == 'god' and Auth::get('id') and !Misc::isGod()) {
      throw new NgnException("God mode not allowed:\n".
            "Possible reasons:\n".
            "* Current user is not god\n".
            "* Current IP is not presents in developers IPs list\n"
           // "* God mod not allowed"
      );
    }
    if (!AdminModule::isAllowed($this->module))
      throw new NgnException("Admin module '{$this->module}' not allowed");
    $class = ClassCore::nameToClass('CtrlAdmin', $this->module);
    if (Lib::exists($class)) {
      // Убираем лимит времени выполнения скрипта только для админки
      set_time_limit_q(0);
      $this->oController = new $class($this);
    } else {
      throw new NgnException("Module '{$this->module}' not found");
    }
  }
  
  protected function auth() {
    Auth::$errorsText = array(
      Auth::ERROR_AUTH_NO_LOGIN => LANG_AUTH_NO_LOGIN,
      Auth::ERROR_AUTH_USER_NOT_ACTIVE => LANG_AUTH_USER_NOT_ACTIVE,
      Auth::ERROR_AUTH_WRONG_PASS => LANG_AUTH_WRONG_PASS
    );
    parent::auth();
  }
  
}