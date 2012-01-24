<?php

abstract class PageBlockView_subPagesAbstract extends PageBlockViewAbstract {

  public $cssClass = 'pbSubMenu';

  /**
   * @return PagesTreeTpl
   */
  public function getPagesTreeTpl() {
    $linkTpl = '`<a href="`.$link.`"><span>`.$title.`</span></a>`';
    $oPagesTreeTpl = PagesTreeTpl::getObjCached($this->oCC->page['pathData'][1]['id']);
    $oPagesTreeTpl->setNodesBeginTpl('`<ul>`');
    $oPagesTreeTpl->setNodesEndTpl('`</ul></li>`');
    $oPagesTreeTpl->setNodeTpl('`<li id="mi_`.Misc::name2id($name).`"`.(!empty($class) ? ` class="`.$class.`"` : ``).`>`.'.$linkTpl);
    $oPagesTreeTpl->setLeafTpl('`<li id="mi_`.Misc::name2id($name).`"`.(!empty($class) ? ` class="`.$class.`"` : ``).`>`.'.$linkTpl.'.`</li>`');
    $oPagesTreeTpl->setDepthLimit($this->oPBM['settings']['openDepth']);
    $oPagesTreeTpl->setCurrentId(R::get('currentPageId'));
    $oPagesTreeTpl->setBreadcrumbsIds(R::get('breadcrumbsPageIds'));
    return $oPagesTreeTpl;
  }

  public function html() {
    return $this->getPagesTreeTpl()->html();
  }
  
}
