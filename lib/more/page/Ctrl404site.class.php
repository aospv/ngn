<?php

class Ctrl404site extends CtrlPage {
  
  public function dispatch() {
    $this->disablePageLog = true;
    $this->page = new DbModelVirtual(array(
      'title' => '404 — Страница не найдена'
    ));
    parent::dispatch();
  }
  
  public function action_default() {
    header('HTTP/1.0 404 Not Found');
    $this->d['tpl'] = 'errors/404';
  }

}