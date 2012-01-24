<?php

class FieldEThemeSelect extends FieldESelect {

  protected function defineOptions() {
    foreach (glob(STM_PATH.'/themes/*') as $folder) {
      $theme = include $folder.'/theme.php';
      $items[] = array(
        'title' => $theme['data']['title'],
        'id' => basename($folder)
      );
    }
    $options[''] = '------------';
    foreach (Arr::sort_by_order_key($items, 'id') as $v) {
      $options['ngn:'.$v['id']] = $v['title']." ({$v['id']})";
    }
    $this->options['options'] = $options;
  }
  
}

class FormThemeUpdate extends Form {

  public function __construct() {
    parent::__construct(new Fields(array(array(
      'title' => 'Тема',
      'type' => 'themeSelect',
      'name' => 'theme'
    ))));
    $this->setElementsData(Config::getVar('theme'));
  }
  
  protected function _update(array $data) {
    StmCore::updateCurrentTheme($data['theme']);
  }
  
}

class CtrlAdminStm extends CtrlAdmin {

  static $properties = array(
    'title' => 'Тема',
    'onMenu' => true,
    'order' => 330
  );
  
  public function action_default() {
    $this->redirect(Tt::getPath(2).'/editTheme/'.
      implode('/', explode(':', Config::getVarVar('theme', 'theme'))));
  }
  
  public function action_setTheme() {
    SiteConfig::updateSubVar('theme', 'theme',
      $this->params[3].':'.$this->params[5].':'.$this->params[6]
    );
    $this->redirect(Tt::getPath(2));
  }
  
  public function action_changeTheme() {
    $oF = new FormThemeUpdate();
    $this->d['form'] = $oF->html();
    if ($oF->update()) $this->redirect();
    $this->d['tpl'] = 'common/form';
  }
  
  public function action_deleteFile() {
    die2($this->oReq->r);
    $oDM = new StmThemeDataManager($this->getCurThemeData());
  }
  
  // --------------------------------------------------------------------
  
  public function action_menuList() {
    $this->d['items'] = O::get('StmMenuDataSet')->items;
  }
  
  public function action_editMenu() {
    $oDM = $this->getStmMenuDM();
    if ($oDM->requestUpdateCurrent()) {
      $this->redirect();
      return;
    }
    $this->setPageTitle('Редактирование меню');
    $this->d['form'] = $oDM->oForm->html();
  }
  
  public function action_updateMenu() {
    $this->getStmMenuDM()->requestUpdateCurrent();
  }
  public function action_ajax_updateMenu() {
    $this->ajaxSuccess = $this->getStmMenuDM()->requestUpdateCurrent();
  }
  
  public function action_menuNewStep1() {
    $this->setPageTitle('Создание меню. Выбор расположения');
    $this->initLocationForm();
    if ($this->oLF->isSubmittedAndValid()) {
      $this->redirect(Tt::getPath(2).'/menuNewStep2/'.$this->oLF->data['location']);
      return;
    }
    $this->d['form'] = $this->oLF->html();
    $this->d['tpl'] = 'stm/editMenu';
  }
  
  public function action_menuNewStep2() {
    $this->setPageTitle('Создание меню. Выбор типа');
    $oF = new Form(new Fields(array(
      array(
        'title' => 'Тип меню',
        'name' => 'menuType',
        'type' => 'select',
        'required' => true,
        'options' => Arr::get(StmMenuStructures::$structures, 'title', 'KEY')
      )
    )), array('submitTitle' => 'Продолжить создание »'));
    if ($oF->isSubmittedAndValid()) {
      $this->redirect(Tt::getPath(2).'/menuNew/'.$this->getParam(3).'/'.
        $oF->data['menuType']);
      return;
    }
    $this->d['form'] = $oF->html();
    $this->d['tpl'] = 'stm/form';
  }
  
  public function action_menuNew() {
    $oSMDS = new StmMenuDataSource(array(
      'location' => $this->getParam(3),
      'menuType' => $this->getParam(4)
    ));
    $this->setPageTitle('Создание нового меню типа «<b>'.
      $oSMDS->structure['title'].'</b>»');
    $oF = new StmForm(array(
      'oSDS' => $oSMDS,
      'submitTitle' => 'Создать'
    ));
    if ($oF->update()) {
      $this->redirect(Tt::getPath(2).'/menuList');
      return;
    }
    $this->d['form'] = $oF->html();
    $this->d['tpl'] = 'stm/editMenu';
  }

  /**
   * @var Form
   */
  protected $oLF;
  
  protected function initLocationForm() {
    $this->oLF = new Form(new Fields(array(
      array(
        'title' => 'Расположение',
        'name' => 'location',
        'type' => 'select',
        'required' => true,
        'options' => Arr::get(Arr::filter_by_value(
          StmLocations::$locations,
        'canEdit', true, true), 'title', 'KEY')
      )
    )), array('submitTitle' => 'Продолжить создание »'));
  }
  
  public function action_themeNewStep1() {
    $this->setPageTitle('Создание темы. Выбор расположения');
    $this->initLocationForm();
    if ($this->oLF->isSubmittedAndValid()) {
      $this->redirect(Tt::getPath(2).'/themeNewStep2/'.$this->oLF->elementsData['location']);
      return;
    }
    $this->d['form'] = $this->oLF->html();
    $this->d['tpl'] = 'stm/editTheme';
  }

  public function action_themeNewStep2() {
    $this->setPageTitle('Создание темы. Выбор дизайна');
    $oF = new Form(new Fields(array(
      array(
        'title' => 'Дизайн',
        'name' => 'design',
        'type' => 'select',
        'required' => true,
        'options' => Arr::get(O::get('StmDesigns',
          array('siteSet' => SITE_SET))->designs, 'title', 'KEY')
      )
    )), array('submitTitle' => 'Продолжить создание »'));
    if ($oF->isSubmittedAndValid()) {
      $this->redirect(Tt::getPath(2).'/themeNew/'.$this->getParam(3).'/'.
        SITE_SET.'/'.str_replace(':', '/', $oF->elementsData['design']));
      return;
    }
    $this->d['form'] = $oF->html();
    $this->d['tpl'] = 'stm/editTheme';
  }
  
  public function action_themeNew() {
    $oDM = new StmThemeDataManager(array(
      'type' => 'theme',
      'subType' => 'design',
      'location' => $this->getParam(3),
      'siteSet' => $this->getParam(4),
      'design' => $this->getParam(5)
    ));
    $this->setPageTitle(
      'Создание новой темы дизайна «<b>'.$oDM->oSD->oSDS->structure['title'].'</b>»');
    if ($oDM->requestCreate()) {
      $this->redirect(Tt::getPath(2));
      return;
    }
    $this->d['form'] = $oDM->oForm->html();
    $this->d['tpl'] = 'stm/editTheme';
  }
  
  protected function getStmThemeDM() {
    return new StmThemeDataManager(array(
      'type' => 'theme',
      'subType' => 'design',
      'location' => $this->getParam(3),
      'id' => $this->getParam(4),
    ));
  }
  
  protected function getStmMenuDM() {
    return new StmMenuDataManager(array(
      'type' => 'menu',
      'subType' => 'menu',
      'location' => $this->getParam(3),
      'id' => $this->getParam(4)
    ));
  }
  
  public function action_editTheme() {
    $oDM = $this->getStmThemeDM();
    if ($oDM->requestUpdateCurrent()) {
      $this->redirect();
      return;
    }
    $this->setPageTitle('Редактирование темы «<b>'.$oDM->getStmData()->data['data']['title'].'</b>» дизайна «<b>'.$oDM->getStmData()->getStructure()->str['title'].'</b>»');
    $this->d['form'] = $oDM->oForm->html();
  }
    
  public function action_ajax_updateTheme() {
    $this->ajaxSuccess = $this->getStmThemeDM()->requestUpdateCurrent();
  }
  
  public function action_deleteTheme() {
    $this->initThemeEditForm();
    $this->oSD->delete();
    $this->redirect(Tt::getPath(2));
  }
  
  public function action_json_themeFancyUpload() {
    $this->getStmThemeDM()->updateFileCurrent($_FILES['Filedata']['tmp_name'], $this->oReq->reqNotEmpty('fn'));
  }
  
  public function action_json_menuFancyUpload() {
    $this->json['success'] = 
      $this->getStmMenuDM()->updateFileCurrent($_FILES['Filedata']['tmp_name'], $this->oReq->reqNotEmpty('fn'));
  }
  
}
