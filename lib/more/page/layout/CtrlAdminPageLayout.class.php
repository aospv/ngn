<?php

class CtrlAdminPageLayout extends CtrlAdminPagesBase {

  static $properties = array(
    'title' => 'Формат страницы',
    'order' => 310,
    'onMenu' => true,
    'class' => 'layout'
  );

  //protected $allowSlavePage = false;
  
  protected function init() {
    parent::init();
    if ($this->pageId) {
      $this->setPageTitle('Формат страницы для раздела «<b>'.$this->page['title'].'</b>»');
    } else {
      $this->setPageTitle('Формат страницы <b>по умолчанию</b>');
    }
    if (($pageIds = array_keys(PageLayoutN::getItems()))) {
      $this->d['layoutPages'] = db()->query(
      'SELECT id, title, path FROM pages WHERE id IN (?a)', $pageIds);
    }
  }

  /*
  public function action_default() {
    $options = PageLayout::getLayouts();
    foreach (array_keys($options) as $k)
      $options[$k] = '<img src="/i/img/layout/'.$k.'.gif">';
    $this->d['layoutType'] = PageLayoutN::get($this->pageId);
    $fields = array(
      array(
        'title' => '',
        'name' => 'layoutType',
        'type' => 'radio',
        'options' => $options,
        'default' => $this->d['layoutType']
      )
    );
    if ($this->pageId) {
      $fields[] = array(
        'title' => 'Восстановить формат по умолчанию',
        'name' => 'delete',
        'type' => 'button'
      );
    }
    $oF = new Form(new Fields($fields));
    if (!$this->pageId)
      $oF->submitTitle = 'Изменить формат страницы по умолчанию';
    else
      $oF->submitTitle = 'Изменить формат для страницы «'.$this->page['title'].'»';
    if ($oF->isSubmittedAndValid()) {
      PageLayoutN::save($this->pageId, $data['layoutType']);
    }
    $this->d['form'] = $oF->html();
    $this->d['tpl'] = 'pageLayout/default';
  }
  */
  
  public function action_default() {
    $this->d['layouts'] = PageLayout::getLayouts();
    $this->d['layoutN'] = PageLayoutN::get($this->pageId);
  }
  
  public function action_ajax_updateLayoutN() {
    PageLayoutN::save($this->pageId, $this->oReq->r['layoutN']);
  }
  
  public function action_delete() {
    PageLayoutN::delete($this->pageId);
    $this->redirect();
  }
  
}