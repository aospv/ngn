<?php

class DispatcherScripts extends Dispatcher {
  
  /**
   * Тип контроллера: scripts/controllers
   *
   * @var string
   */
  private $controllerType;
  
  protected function init() {
    $this->controllerType = $this->oReq->params[0][0] == 's' ? 
                            'scripts' : 'controllers';
    if ($this->oReq->params[0] == 's2' or $this->oReq->params[0] == 'c2') {
      $this->isDB = false;
    } else {
      $this->isDB = true;
    }
    parent::init();
  }
  
  protected function initController() {
    if ($this->controllerType == 'scripts') {
      // Для JavaScript'ов и CSS:
      // - включить PLAIN TEXT режим
      // - выключить нотисы
      $staticFilesMode = (
        isset($this->oReq->params[1]) and 
        ($this->oReq->params[1] == 'js' or $this->oReq->params[1] == 'css')
      ) ? true : false;
      if ($staticFilesMode) {
        R::set('plainText', true);
      }
      // ----------------------------------------
      $this->oController = new CtrlScripts($this);
      $this->oController->folder = $this->isDB ? 'scripts' : 'scripts_noDb';
      // ----------------------------------------
      if ($staticFilesMode)
        Err::noticeSwitch(true);      
    } else {
      if (($class = ClassCore::getExistingClass('CtrlCommon', array(
        $this->oReq->params[count($this->oReq->params)-1],
        $this->oReq->params[1]
      ))) === false)
        Err::error("Controller not found by path: ".Tt::getPath());
      $this->oController = O::get($class, $this);
    }
  }
  
}