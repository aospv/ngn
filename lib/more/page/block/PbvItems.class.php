<?php

class PbvItems extends PbvAbstract {

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
    $this->page = DbModelCore::get('pages', $this->oPBM['settings']['pageId']);
    $this->initItems();
  }
  
  protected function initItems() {
    $this->oItems->cond->setLimit(!empty($this->oPBM['settings']['limit']) ?
      $this->oPBM['settings']['limit'] : 5);
    $this->oItems->cond->setOrder(!empty($this->oPBM['settings']['order']) ?
      $this->oPBM['settings']['order'] : 'dateCreate DESC');
  }
  
  protected function initButtons() {
    if (strstr($this->oPBM['settings']['order'], 'DESC')) {
      $orderPath = 'o.'.str_replace(' DESC', '', $this->oPBM['settings']['order']);
    } else {
      $orderPath = 'oa.'.$this->oPBM['settings']['order'];
    }
    $this->moreLink = array(
      'title' => $this->oPBM['settings']['listBtnTitle'] ? 
        $this->oPBM['settings']['listBtnTitle'] : 'все',
      'link' => $this->oPBM['settings']['listBtnOrder'] == 'block' ?
        $this->page['path'].'/'.$orderPath : $this->page['path']
    );
    if (!empty($this->page['settings']['rssTitleField'])) {
      $this->buttons[] = array(
        'title' => 'RSS «'.$this->page['title'].'»',
        'class' => 'rss',
        'link' => $this->page['path'].'?a=rss'
      );
    }
  }
  
  public function _html() {
    $this->items = $this->oItems->getItems();
    $oDdo = DdoSiteFactory::get($this->page, 'pageBlock');
    $oDdo->ddddByName['more'] =
      '`<a href="`.Tt::getPath(0).$pagePath.`/`.$id.`"><span>`.$title.`</span></a>`';
    $oDdo->setItems($this->items);
    $d['oDdo'] = $oDdo;
    return Tt::getTpl('pageBlocks/items', $d);
  }
  
}