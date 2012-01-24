<?php

class SubPaDdSlave extends SubPa {

  /**
   * @var CtrlDdItems
   */
  protected $oPA;
  
  protected $user;
  
  public $masterItem;
  
  public function init() {
    $masterStrName = DdCore::getMasterStrName($this->oPA->strName);
    if (!empty($this->oPA->itemId)) {
      $this->oPA->setItemData();
      if (empty($this->oPA->itemData)) {
        array_pop($this->oPA->d['pathData']);
        $this->disable = true;
        return;
      }
      $this->masterItem = $this->oPA->itemData[DdCore::masterFieldName];
    } else {
      // Необходимо получить запись, т.к. $this->itemData существует только
      // при выводе одной записи
      /* @var $oDdItems DdItems */
      $oDdItems = O::get('DdItems', $this->oPA->page['parentId']);
      $oDdItems->strict = true;
      if (!isset($this->oPA->d['filters']['v'][0]) or
      $this->oPA->d['filters']['v'][0] != DdCore::masterFieldName) {
        throw new NgnException('Filter "'.DdCore::masterFieldName.'" must be defined');
        return;
      }
      $this->masterItem = $oDdItems->getItemF($this->oPA->d['filters']['v'][1]);
    }
    if (empty($this->masterItem)) throw new EmptyException('$this->masterItem');
    if ($this->masterItem['authorId'] == Auth::get('id')) {
      $this->oPA->setPrivs(array('edit', 'create'), true);
    }
    $this->oPA->d['masterItem'] = $this->masterItem;
    // author mode
    $this->initTitle();
    if ($this->oPA->page->getS('ownerMode') == 'author') {
      $this->user = $this->oPA->d['itemUser'] =
        DbModelCore::get('users', $this->masterItem['userId']);
      $this->initUserMenu();
    }
  }
  
  protected function initUserMenu() {
    if ($this->oPA->action != 'list' or $this->oPA->page->getS('ownerMode') != 'author') return;
    $this->oPA->d['submenu'] = UserMenu::get(
      $this->user,
      $this->oPA->page['id'],
      $this->oPA->action
    );
  }

  public function setItemsOnItem() {
    $this->oPA->oManager->oItems->cond->addF(
      $this->masterField['name'],
      $this->masterItem['id']
    );
    $this->oPA->setItemsOnItem();
  }
  
  protected $title;
  
  protected function initTitle() {
    if (!empty($this->masterItem['title']))
      $this->title = $this->masterItem['title'];
    elseif (!empty($this->oPA->settings['itemTitle']))
      $this->title = $this->oPA->settings['itemTitle'];
    if (isset($this->title) and $this->oPA->action == 'list')
      $this->oPA->setPageTitle($this->title);
  }
  
  public function initAuthorPath() {
    if ($this->oPA->page->getS('ownerMode') != 'author') return;
    Misc::checkEmpty($this->user);
    $masterN = count($this->oPA->d['pathData'])-2;
    $this->oPA->d['pathData'] = Arr::injectAfter($this->oPA->d['pathData'],
      $masterN, array(
        'link' => $this->oPA->d['pathData'][$masterN]['link'].'/u.'.$this->user['id'],
        'title' => $this->user['login']
      ));
  }
  
  public function initPath() {
    $this->oPA->callDirect('initPath');
    // Если это slave-контроллер, то ссылку на этот раздел без фильтра нужно убрать
    if ($this->oPA->adminMode or !$this->oPA->page['slave']) return;
    if (!isset($this->oPA->itemData)) {
      $replacedPathN = count($this->oPA->d['pathData'])-1;
    } else {
      $replacedPathN = count($this->oPA->d['pathData'])-2;
    }
    $this->oPA->d['pathData'][$replacedPathN]['link'] .= '/v.'.
      DdCore::masterFieldName.'.'.$this->masterItem['id'];
    $this->oPA->d['pathData'][$replacedPathN]['title'] = $this->title ?: '{empty}';
  }
  
}