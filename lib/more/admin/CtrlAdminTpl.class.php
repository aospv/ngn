<?php

class CtrlAdminTpl extends CtrlAdmin {

  static $properties = array(
    'title' => 'Шаблоны',
    'onMenu' => false
  );
  
  private $tplPath;
  
  function init() {
    $this->tplPath = Tpl::returnSlashes($this->params[3]);
  }
  
  function action_default() {
    $this->setPageTitle('Настройки шаблонов');
    $this->d['tpl'] = 'tpl/default';
    $this->d['lists']['ngn'] = Tpl::getListNGN();
    //$this->d['lists']['master'] = Tpl::getListMaster();
    //$this->d['lists']['site'] = Tpl::getListSite();
    $this->d['lists']['theme'] = Tpl::getListTheme();
  }
  
  function action_editSettings() {
    if (!$this->tplPath) throw new NgnException('$this->tplPath not defined');
    $this->setPageTitle('Редактирование настроек шаблона «'.$this->tplPath.'»');
    /* @var $oF Form */
    $oF = O::get('core/Form', O::get('core/Field', Tpl::getSettingsFields($this->tplPath)));
    $data = $oF->setElementsData(Tpl::getSettings($this->tplPath));
    if ($oF->isSubmittedAndValid()) {
      $this->d['saved'] = true;
      Tpl::saveSettings($this->tplPath, $data);
    }
    $this->d['form'] = $oF->html();
    $this->d['tpl'] = 'tpl/edit';
  }

}
