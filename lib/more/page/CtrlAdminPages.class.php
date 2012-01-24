<?php

class CtrlAdminPages extends CtrlAdminPagesBase {

  static $properties = array(
    'title' => 'Разделы сайта',
    'onMenu' => true,
    'order' => 5
  );
  
  protected function init() {
    parent::init();
    if (!$this->folder and $this->action == $this->defaultAction) {
      if (!empty($this->page['controller']) and
      PageControllersCore::isEditebleContent($this->page['controller'])) {
        //$this->redirect(Tt::getPath(3).'/editContent');
        //return;
        //$this->addSubController($sub);
      } else {
        $this->redirect(Tt::getPath(3).'/editPage');
        return;
      }
    }
  }
  
  public function addSubControllers() {
    if ($this->getParamAction() == 'editContent') {
      $this->allowRequestAction = false;
      $class = AdminPagesCore::getEditContentSubController($this->page['controller']);
      if ($class) $this->addSubController(O::get($class, $this));
    }
  }
  
  public function action_newPage() {
    $this->d['controllers'] = $this->getPageControllerOptions();
    $this->d['tpl'] = 'pages/editPage';
    $this->d['postAction'] = 'createPage';
    $this->setPageTitle(LANG_CREATE_NEW_SECTION);
  }
  
  public function action_ajax_delete() {
    DbModelCore::delete('pages', $this->oReq->r['id']);
  }
  public function action_ajax_activate() {
    DbModelPages::getTree()->updatePropertyWithChildren($this->oReq->r['id'], 'active', 1);
  }  
  
  public function action_ajax_deactivate() {
    DbModelPages::getTree()->updatePropertyWithChildren($this->oReq->r['id'], 'active', 0);
  }

  public function action_ajax_onMenu() {
    DbModelCore::update('pages', $this->oReq->r['id'], array('onMenu' => $this->oReq->r['onMenu']));
  }
  
  public function action_json_editControllerSettings() {
    if (empty($this->page['controller']))
      throw new EmptyException("\$this->page['controller']");
    $oF = new PageControllerSettingsForm($this->page);
    if ($oF->update()) {
      if (!empty($this->page['controller'])) {
        // Дополнительные диалоги, после схоранения св-в контроллера
        if (($dialogs = PageControllersCore::getPropObj(
        $this->page['controller'])->getAfterSaveDialogs($oF)) !== false) {
          $this->json['dialogs'] = $dialogs;
          return;
        }
      }
      return;
    }
    $this->json['title'] = LANG_CONTROLLER_OPTIONS.' <b>«'.
      PageControllersCore::getPropObj($this->page['controller'])->title.
      '»</b> '.LANG_SECTION.' <b>«'.$this->d['page']['title'].'»</b>';
    return $oF;
  }

  public function action_default() {
    $this->d['parent'] = DbModelCore::get('pages', $this->parentId);
    $this->d['items'] = DbModelPages::getTree()->getChildren($this->pageId);
    $this->setPageTitle($this->d['parent']['title']);
  }
  
  public function action_ajax_reload() {
    $this->d['items'] = DbModelPages::getTree()->getChildren($this->pageId);
    $this->ajaxOutput = Tt::getTpl('admin/modules/pages/itemsTable', $this->d);
  }
  
  protected function initPathDd() {
    if (!isset($this->oSPC)) throw new NgnException('$this->oSPC not defined');
    if (!isset($this->oSPC->masterItem)) return;
    if (empty($this->oSPC->masterItem['title']))
      throw new NgnException("\$this->oSPC->masterItem['title'] is empty.");
    $this->d['path'][count($this->d['path'])-1] = array(
      'title' => $this->oSPC->masterItem['title'],
      'name' => 'page_edit',
      'link' => Tt::getPath()
    );
  }
  
  public function action_json_editItemSystemDates() {
    $this->json['title'] = 'Редактирование дат';
    $itemId = $this->getParam(4);
    $oIM = new DdItemSystemDatesManager(
      DbModelCore::get('pages', $this->page['id'])->r['strName'],
      $itemId
    );
    if ($oIM->requestUpdate($itemId)) return;
    return $oIM->oForm;
  }
  
  /**
   * 1) Создает поле 'oid', если его нет
   * 2) Выставляет в настройках раздела поле сортировки
   *
   */
  public function action_setOidPageOrder() {
    if (!$strName = $this->page['settings']['strName'])
      throw new NgnException('$strName not defined');
    $oF = O::get('DdFields', $strName);
    if (!$oF->getField('oid')) {
      $oF->create(array(
        'title' => LANG_ORDER_NUM,
        'name' => 'oid',
        'type' => 'num',
        'system' => true,
        'oid' => 300
      ));
    }
    DbModelPages::addSettings($this->page['id'], array('order' => 'oid'));
    $this->redirect('referer');
  }

  /**
   * 1) Удаляет поле 'oid', если оно есть
   * 2) Обнуляет поле сортировки
   */
  public function action_resetOidPageOrder() {
    if (!$strName = $this->page['settings']['strName'])
      throw new NgnException('$strName not defined');
    $oF = O::get('DdFields', $strName);
    $field = $oF->getDataByName('oid');
    if ($field['id']) $oF->delete($field['id']);
    DbModelPages::addSettings($this->page['id'], array('order' => ''));
    $this->redirect('referer');
  }
  
  public function action_setRatingOn() {
    $oRI = new RatingInstaller();
    $oRI->install($this->page['id']);
    $this->redirect('referer');
  }
  
  public function action_setRatingOff() {
    $oRI = new RatingInstaller();
    $oRI->uninstall($this->page['id']);
    $this->redirect('referer');
  }
  
  protected function getControllerRequiredFieldsHtml($controller) {
    $oF = new DdFormBase(
      new PageControllerSettingsFields($controller),
      null
    );
    $oF->setNameArray('settings');
    if (!$oF->oFields->getRequired()) return '';
    $oF->onlyRequired = true;
    $oF->disableSubmit = true;
    $oF->disableFormTag = true;
    $oF->setElementsData(DbModelCore::get('pages', $this->pageId)->r['initSettings']);
    return $oF->html();
  }
  
  public function action_ajax_controllerRequiredFields() {
    if (empty($this->oReq->r['controller'])) return;
    $this->ajaxOutput = $this->getControllerRequiredFieldsHtml($this->oReq->rq('controller'));
  }

  public function action_json_newModulePage() {
    return $this->actionJsonFormUpdate(new NewModulePageForm($this->pageId));
  }
  
  public function action_json_newPage() {
    return $this->actionJsonFormUpdate(new NewPageForm($this->pageId));
  }
  
  public function action_json_controllerRequiredFields() {
    $this->json = O::get('PageControllerSettingsFields', $this->oReq->rq('controller'))->getFields();
  }
  
  public function action_json_editPageProp() {
    return $this->actionJsonFormUpdate(new EditPagePropForm($this->pageId, $this->god));
  }
  
  public function action_json_getTree() {
    $this->json = O::get('MifTreePages')->getTree();
  }
  
  public function action_ajax_reorder() {
    DbShift::items($this->oReq->rq('ids'), 'pages');
  }
  
  public function action_ajax_move() {
    DbModelPages::move($this->oReq->rq('id'), $this->oReq->rq('toId'), $this->oReq->rq('where'));
  }
  
  protected function extendTplDataTagFilter() {
    if (empty($this->d['pcd']['settings']['tagField'])) return;
    $tagField = $this->d['pcd']['settings']['tagField'];
    $tagOptions = array('' => '- без фильтра -');
    if (isset($this->d['pcd']['tags'][$tagField])) {
      $isTree = strstr($this->d['pcd']['fields'][$tagField]['type'], 'Tree');
      if ($isTree) {
        $this->d['pcd']['tags'][$tagField] =
          DdTagsHtml::treeToList($this->d['pcd']['tags'][$tagField]); 
      }
      foreach ($this->d['pcd']['tags'][$tagField] as $v) {
        // Формируем список тегов
        $isTree ?
          // Для древовидного типа
          $tagOptions[$v['id']] = str_repeat('&bull; ', $v['depth']).
            $v['title']." ({$v['cnt']})" :
        // Для линейного типа
        $tagOptions[$v['name']] = $v['title']." ({$v['cnt']})";
      }
    }
    if (isset($this->d['pcd']['tagsSelected'])) {
      if (($tagSelected = Arr::first($this->d['pcd']['tagsSelected'])) !== false) {
        $this->d['path'][] = array(
          'title' => $tagSelected['title'],
          'link' => Tt::getPath()
        );
      }
    }
    $this->d['filter'] = array(
      'name' => $tagField,
      'param' => 't2',
      'title' => $this->d['pcd']['fields'][$tagField]['title'],
      'options' => $tagOptions
    );
    $this->d['filter']['selected'] = $this->d['pcd']['filters']['t2'][1];
  }
  
  protected function extendTplDataDdItemsFilter() {
    // Получаем данные для фильтра по полю slave-полу типа ddItemsSelect
    if (isset($this->d['oSPC']) and $this->d['oSPC']->masterPageId) {
      $oI = new DdItems($this->d['oSPC']->masterPageId);
      $filterOptions = array('' => '- без фильтра -');
      $filterOptions += Arr::get($oI->getItems(), 'title', 'id');
      $this->d['filter'] = array(
        'name' => $this->d['oSPC']->masterField['name'],
        'param' => 'v',
        'title' => $this->d['oSPC']->masterField['title'],
        'options' => $filterOptions
      );
      $this->d['filter']['selected'] = $this->d['pcd']['filters']['v'][1];
    }
  }
  
  protected function extendTplData() {
    parent::extendTplData();
    $this->extendTplDataTagFilter();
    $this->extendTplDataDdItemsFilter();
    if (!empty($this->d['page']['module']))
      $this->extendMainContentCssClass('pageModule_'.$this->d['page']['module']);
  }
  
}
