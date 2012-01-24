<?php

/**
 * @todo Реализовать возможность просмотра рядышком всех Разделов, в 
 *       которых используется данная Структура
 *
 */
class CtrlAdminDdStructure extends CtrlAdmin {

  static $properties = array(
    'title' => 'Структуры',
    'onMenu' => true,
    'order' => 30
  );
  
  protected $prepareMainFormTpl = true;
  
  /**
   * @var DdStructuresManager
   */
  protected $oIM;
  
  protected function init() {
    $this->oIM = new DdStructuresManager();
  }
  
  public function action_default() {
    $this->d['items'] = $this->oIM->oItems->getItems();
  }

  public function action_edit() {
    $data = $this->oIM->oItems->getItem($this->oReq->rq('id'));
    $this->setPageTitle('Редактирование структуры «'.$data['title'].'»');
    if ($this->oIM->requestUpdate($this->oReq->rq('id'))) {
      $this->redirect(Tt::getPath(2));
    }
    $this->d['form'] = $this->oIM->oForm->html();
  }
  
  public function action_new() {
    $this->setPageTitle(LANG_STRUCTURE_CREATING);
    if ($this->oIM->requestCreate()) {
      $this->redirect(Tt::getPath(2));
    }
    $this->d['form'] = $this->oIM->oForm->html();
  }
  
  public function action_delete() {
    $this->oIM->delete($this->oReq->rq('id'));
    $this->redirect();
  }
  
}
