<?php

class Ctrl404 extends CtrlCommon {
  
  public function action_default() {
    header('HTTP/1.0 404 Not Found');
    $this->setPageTitle('404 — Страница не найдена');
    $this->d['tpl'] = 'errors/404';
  }
  
}
