<?php

abstract class PbvSubPagesAbstract extends PbvAbstract {

  public $cssClass = 'pbSubMenu';
  
  abstract protected function getPageId();
  
  /**
   * @var PagesTreeTpl
   */
  protected $oPagesTreeTpl;
  
  protected function init() {
    if (($pageId = $this->getPageId()) === false) return;
    $oPagesTreeTpl = PagesTreeTpl::getObjCached($pageId);
    $linkTpl = '`<a href="`.$link.`"><span>`.$title.`</span></a>`';
    $oPagesTreeTpl->setNodesBeginTpl('`<ul>`');
    $oPagesTreeTpl->setNodesEndTpl('`</ul></li>`');
    $oPagesTreeTpl->setNodeTpl('`<li id="mi_`.Misc::name2id($name).`"`.(!empty($class) ? ` class="`.$class.`"` : ``).`>`.'.$linkTpl);
    $oPagesTreeTpl->setLeafTpl('`<li id="mi_`.Misc::name2id($name).`"`.(!empty($class) ? ` class="`.$class.`"` : ``).`>`.'.$linkTpl.'.`</li>`');
    $oPagesTreeTpl->setDepthLimit($this->oPBM['settings']['openDepth']);
    if (($currentPageId = R::get('currentPageId')) !== false)
      $oPagesTreeTpl->setCurrentId($currentPageId);
    $oPagesTreeTpl->setBreadcrumbsIds(R::get('breadcrumbsPageIds'));
    $this->oPagesTreeTpl = $oPagesTreeTpl;
  }

  /**
   * @return PagesTreeTpl
   */
  public function getPagesTreeTpl() {
  }

  public function _html() {
    if (!isset($this->oPagesTreeTpl)) return '';
    //$js = 'new ';
    return $this->oPagesTreeTpl->html();
  }
  
}
