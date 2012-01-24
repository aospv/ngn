<?php

class CtrlBlank extends CtrlCommon {
  
  protected function beforeInit() {
    Lang::load('admin');
  }
  
  protected function setTheme() {
    $this->theme = !defined('ADMIN_THEME') ? 'admin' : ADMIN_THEME;
    $this->d['theme'] = $this->theme;
  }
  
}
