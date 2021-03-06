<?php

class PageBlockView_items extends PageBlockViewAbstract {

  /**
   * @var DbModelPages
   */
  protected $page;
  
  /**
   * @var DdItems
   */
  public $oItems;
  
  protected $items;
  
  protected function init() {
    $this->oItems = O::get('DdItems', $this->oPBM['settings']['pageId']);
    $this->initItems();
  }
  
  protected function initItems() {
    $this->oItems->cond->setLimit(!empty($this->oPBM['settings']['limit']) ?
      $this->oPBM['settings']['limit'] : 5);
    $this->oItems->cond->setOrder(!empty($this->oPBM['settings']['order']) ?
      $this->oPBM['settings']['order'] : 'dateCreate DESC');
  }
  
  public function html() {
    $this->items = $this->oItems->getItems();
    $this->page = DbModelCore::get('pages', $this->oPBM['settings']['pageId']);
    $oDdo = DdoSiteFactory::get($this->page, 'pageBlock');
    $oDdo->ddddByName['more'] =
      '`<a href="`.Tt::getPath(0).$pagePath.`/`.$id.`"><span>`.$title.`</span></a>`';
    $oDdo->setItems($this->items);
    if (strstr($this->oPBM['settings']['order'], 'DESC')) {
      $orderPath = 'o.'.str_replace(' DESC', '', $this->oPBM['settings']['order']);
    } else {
      $orderPath = 'oa.'.$this->oPBM['settings']['order'];
    }
    $d = array(
      'title' => $this->oPBM['settings']['title'],
      'listBtnTitle' => $this->oPBM['settings']['listBtnTitle'],
      'listPath' => $this->oPBM['settings']['listBtnOrder'] == 'block' ?
        $this->page['path'].'/'.$orderPath : $this->page['path'],
      'path' => $this->page['path'],
      'isRss' => !empty($this->page['settings']['rssTitleField']),
      'pageTitle' => $this->page['title'],
    );
    $d['oDdo'] = $oDdo;
    return Tt::getTpl('pageBlocks/items', $d);
  }
  
}