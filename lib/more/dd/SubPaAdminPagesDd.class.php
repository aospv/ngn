<?php

class SubPaAdminPagesDd extends SubPa {

  /**
   * Sub Page Controller
   *
   * @var CtrlPage
   */
  
  public $oSPC;
  
  public function init() {
    if (!PageControllersCore::exists($this->oPA->page['controller']))
      throw new NgnException(
      'Controiller class of module "'.$this->oPA->page['controller'].'" not exists.');
    $oReq = new Req();
    $oReq->params = Arr::get_from($oReq->params, 3);
    $this->oPA->d['oSPC'] = $this->oSPC = PageControllersCore::getController(
      $this->oPA->oDispatcher,
      $this->oPA->page,
      array('oReq' => $oReq)
    );
    $this->oSPC->strictFilters = false; // Не используем жесткие фильтры
    if (isset($this->oReq->r['saveAndReturn'])) {
      $this->oSPC->completeRedirectType = 'fullpath';
    } else { 
      $this->oSPC->completeRedirectType = 'self';
    }
    // Отключаем в настройках экшн по умолчанию, если его значение - 'blocks'
    if (isset($this->oPA->page['settings']['defaultAction']) and
    $this->oPA->page['settings']['defaultAction'] == 'blocks')
      $this->oPA->page['settings']['defaultAction'] = '';
    $this->oSPC->setAdminMode(true);
  }

  public function action_editContent() {
    // Разделы не имеющие контроллера не могут иметь редактирование содержания
    if (!$this->oPA->page['controller']) {
      $this->redirect(Tt::getPath(2).'/'.$this->oPA->page['parentId']);
      return;
    }
    //$this->oSPC->params[0] = 'dummy';
    $this->oSPC->dispatch();
    if ($this->oSPC->isError404) {
      die2($this->oSPC->d['text']);
      return;
    }
    // Если экшн без вывода меняем контроллер диспетчера на текущий сабконтролер раздела
    if (!$this->oSPC->hasOutput) {
      $this->oPA->oDispatcher->oController = $this->oSPC;
      return;
    }
    // Редирект для slave-раздела без фильтра на фильтр
    if (
      isset($this->oSPC->subControllers['ddSlave']) and
      $this->oSPC->action == 'list'
    ) {
      if ($this->oSPC->d['filters']['v'][0] != DdCore::masterFieldName) {
        $this->oPA->redirect(
          Tt::getPath(4).'/v.'.DdCore::masterFieldName.'.'.
          db()->firstId(DdCore::table(DdCore::getMasterStrName($this->oPA->page['strName'])))
        );
      }
    }
    $this->initPath();
    if ($this->oSPC->json) {
      print json_encode($this->oSPC->json);
      $this->hasOutput = false;
      return;
    }
    $this->oPA->d['tpl'] = 'pages/editContent';
    $this->oSPC->priv['edit'] = 1;
    $this->oPA->d['pcd'] = $this->oSPC->d; // Page Controller Data
    //$this->oPA->d['pagination'] = $this->oSPC->d['pagination'];
    if (isset($this->oSPC->strName)) {
      $oFields = new DdFields($this->oSPC->strName);
      $this->oPA->d['pcd']['fields'] = $oFields->getFields();
    }
    //$this->oSPC->action();
    if ($this->oPA->d['pcd']['action'] == 'edit' or $this->oPA->d['pcd']['action'] == 'new')
      $this->oPA->d['pcd']['tpl'] = 'dd/form';
    /////////////////////////////////
    $this->oPA->setPageTitle(
      LANG_EDIT_SECTION_CONTENTS.' <b>«'.$this->oSPC->d['page']['title'].'»</b>');
  }
  
  protected function initPath() {
    if (!isset($this->oSPC)) throw new NgnException('$this->oSPC not defined');
    if (!isset($this->oSPC->masterItem)) return;
    if (empty($this->oSPC->masterItem['title']))
      throw new NgnException("\$this->oSPC->masterItem['title'] is empty.");
    $this->oPA->d['path'][count($this->d['path'])-1] = array(
      'title' => $this->oSPC->masterItem['title'],
      'name' => 'page_edit',
      'link' => Tt::getPath()
    );
  }

}