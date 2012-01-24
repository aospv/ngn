<?php

class CtrlCp extends CtrlCommon {

  protected function initMainTpl() {
    $this->d['mainTpl'] = 'cp/main';
  }
  
  protected function beforeInit() {
    $this->initMainTpl();
    Lang::load('admin');
    $this->d['name'] = 'cp';
    $this->d['mainContentCssClass'] = 'mainContent';
  }
  
  protected function setTopLinks(array $links) {
    $this->d['topLinks'] = $links;
  }
  
  protected function setModuleTitle($title) {
    $this->d['moduleTitle'] = $title;
  }
  
  protected function extendMainContentCssClass($class) {
    $this->d['mainContentCssClass'] .= ' '.$class;
  }
  
  protected function extendTplData() {
    $this->extendMainContentCssClass('a_'.$this->d['action']);
    if (!empty($this->d['pageTitle'])) $this->setModuleTitle($this->d['moduleTitle'].' →');
  }

}
