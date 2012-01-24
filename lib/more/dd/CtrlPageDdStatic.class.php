<?php

abstract class CtrlPageDdStatic extends CtrlPageDd {
  
  protected $defaultAction = 'showItem';
  
  public $adminTplFolder = 'item';
  
  protected $staticItemData;
  
  protected function init() {
    parent::init();
    $this->initItemsManager();
    $this->initStaticItemData();
    $this->d['ddType'] = 'ddStatic'; // Необходимо для шаблона модераторского-меню
  }
  
  public function initStaticItemData() {
    /* @var $oDdItems DdItems */
    $oDdItems = O::get('DdItems', $this->page['id']);
    $this->staticItemData = $oDdItems->getItemByField('static_id', $this->oManager->static_id);
    $this->itemId = $this->staticItemData['id'];
  }
  
  public function action_showItem() {
    if (!$this->staticItemData) {
      $this->error404('Содержание страницы не заполнено');
      return;
    }
    $this->d['tpl'] = 'item';
    $this->d['item'] = $this->staticItemData;
  }
  
  public function action_new() {
    if (!empty($this->staticItemData)) {
      // Перебрасываем на страницу редактирования, если запись уже существует
      $this->redirect(Tt::getPath().'?a=edit');
      return false;
    }
    parent::action_new();
  }  
  
  public function action_edit() {
    // Запрещаем редактирование, если данные для записи ещё не существуют
    if (empty($this->staticItemData)) {
      $this->redirect(Tt::getPath().'?a=new');
      return;
    }
    parent::action_edit();
  }
  
  protected function _initAllowedActions() {
    parent::_initAllowedActions();
    Arr::dropArr($this->allowedActions, array('activate', 'deactivate', 'delete'));
  }
  
  protected function getItemsManagerOptions() {
    return array('staticId' => $this->getStaticId());
  }
  
  abstract protected function getStaticId();
  
}
