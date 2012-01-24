<?php

class CtrlAdminDdOutput extends CtrlAdminPagesBase {

  static $properties = array(
    'title' => 'Управление выводом полей',
    'order' => 30
  );

  protected $moduleName;
  
  protected $layouts;
  
  protected $curLayoutName;
  
  /**
   * @var DdoSettings
   */
  protected $oS;
  
  protected function init() {
    Misc::checkEmpty($this->page['strName']);
    $this->oS = new DdoSettings($this->page->getModule());
    $this->layouts = $this->oS->getLayouts();
    if (isset($this->params[3]) and isset($this->layouts[$this->params[3]]))
      $this->curLayoutName = $this->d['curLayoutName'] = $this->params[3];
    else
      $this->curLayoutName = $this->d['curLayoutName'] = 
        Arr::first_key($this->layouts);
  }
  
  public function action_default() {
    $str = O::get('DbItems', 'dd_structures')->getItemByField('name', $this->page['strName']);
    $this->setPageTitle('Управление выводом полей структуры «<b>'.$str['title'].'</b>» модуля «<b>'.
      PageModuleCore::getTitle($this->page).'</b>»');
    $this->d['oS'] = $this->oS;
    $this->d['settings']['show'] = $this->oS->getShowAll($this->curLayoutName);
    $this->d['settings']['outputMethod'] = $this->oS->getOutputMethod($this->curLayoutName);
    $this->d['layouts'] = $this->layouts;
    $oF = new DdoFields(
      new DdoSettings($this->page->getModule()),
      $this->curLayoutName,
      $this->page['strName'],
      array('getAll' => true)
    );
    $this->d['fields'] = $oF->getFields();
  }
  
  public function action_json_updateFieldsOutputSettings() {
    $this->oS->updateShow(isset($_POST['show']) ? $_POST['show'] : array());
    $this->oS->updateOutputMethod(Arr::filter_empties2($_POST['outputMethod']));
  }
  
  public function action_ajax_reorder() {
    $n = 0;
    foreach ($this->oReq->rq('ids') as $fieldName) {
      $n += 10;
      $oids[$fieldName] = $n;
    }
    $this->oS->updateOrderIds($oids, $this->curLayoutName);
  }
  
}