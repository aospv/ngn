<?php

abstract class CtrlAdminPagesBase extends CtrlAdmin {

  /**
   * ID текущего раздела
   *
   * @var integer
   */
  protected $pageId;
  
  /**
   * ID родительского раздела
   *
   * @var integer
   */
  protected $parentId = 0;
  
  /**
   * Текущая модель раздела
   *
   * @var DbModelPages
   */
  public $page;
  
  /**
   * Флаг, определяющий то, что текущий раздел является каталогом
   *
   * @var bool
   */
  protected $folder;
  
  protected function initParamActionN() {
    $this->paramActionN = 3;
  }
  
  protected function initParams() {
    parent::initParams();
    $this->pageId = isset($this->params[2]) ? $this->params[2] : 0;
    if (!$this->pageId) $this->folder = true;
    else {
      if (($this->page = DbModelCore::get('pages', $this->pageId)) === false) {
        $this->error("Page ID={$this->pageId} not found");
      } else {
        $this->folder = (bool)$this->page['folder'];
        $this->d['page'] = $this->page;
        $this->parentId = $this->page['parentId'];
        $this->d['parentPage'] = DbModelCore::get('pages', $this->parentId);
      }
    }
    $this->initPagesPath();
    $this->initSlave();
  }
  
  /**
   * Определяет, что данный контроллер может запускаться и для slave-разделов
   *
   * @var bool
   */
  protected $allowSlavePage = true;
  
  protected function initSlave() {
    if (!$this->pageId) return;
    if (!ClassCore::hasAncestor('Ctrl'.$this->page['controller'], 'CtrlDd'))
      return;
    if (!$this->allowSlavePage and $this->oSPC->page['slave'])
      throw new NgnException('You can run this controller under slave page');
  }
  
  protected function initPagesPath() {
    $this->d['path'][0] = array(
      'title' => SITE_TITLE,
      'name' => '_root',
      'link' => Tt::getPath(2)
    );
    if ($this->pageId and 
    ($parents = DbModelPages::getTree()->getParentsReverse($this->pageId)) !== false) {
      $n = 1;
      foreach ($parents as $v) {
        $this->d['path'][$n] = array(
          'title' => $v['title'],
          'name' => 'page_'.$v['id'],
          'link' => Tt::getPath(1).'/pages/'.$v['id']
        );
        $n++;
      }
    }
  }
  
  protected function extendTplData() {
    parent::extendTplData();
    if (empty($this->page['module'])) return;
    $file = CORE_PAGE_MODULES_PATH.'/'.$this->page['module'].'/hook.admin.php';
    if (file_exists($file)) include $file;
  }
  
}
