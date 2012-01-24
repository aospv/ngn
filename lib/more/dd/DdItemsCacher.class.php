<?php

class DdItemsCacher {

  /**
   * @var DdItems
   */
  public $oItems;
  
  /**
   * @var DbModelPages
   */
  protected $page;
  
  /**
   * @var NgnCache
   */
  protected $oCache;
  
  /**
   * @var Ddo
   */
  protected $oDdo;
  
  protected $prefix;
  
  protected $ids;
  
  protected $html = array();
  
  static $force = false;
  
  static public function getCache() {
    return NgnCache::c();
  }
  
  public function __construct(DdItems $oItems, $ddoLayout = 'siteItems') {
    $this->oItems = $oItems;
    $this->page = $this->oItems->page;
    $this->prefix = $oItems->table;
    $this->oCache = self::getCache();
    $this->ids = $this->oItems->getItemIds();
    foreach ($this->ids as $id) {
      if (($r = $this->getHtml($id)) !== false)
        $this->html[$id] = $r;
      else
        $absent[] = $id;
    }
    $this->oDdo = DdoSiteFactory::get($this->page, $ddoLayout);
    if (isset($absent)) $this->saveHtml($absent);
  }
  
  protected function getHtml($id) {
    if (self::$force) return false;
    return $this->oCache->load($this->prefix.$id);
  }
  
  protected function saveHtml(array $ids) {
    $this->oItems->cond->addF('id', $ids);
    $this->oDdo->setItems($this->oItems->getItems());
    $html = $this->oDdo->elsSeparate();
    foreach ($html as $id => $v) {
      $this->oCache->save($v, $this->prefix.$id, array('page'.$this->page['id']));
      $this->html[$id] = $v;
    }
    $this->html = Arr::sortByArray($this->html, $this->ids);
  }
  
  public function html() {
    return
      $this->oDdo->itemsBegin().
      implode('', $this->html).
      $this->oDdo->itemsEnd();
  }
  
  static public function cc($strName, $id) {
    self::getCache()->remove(DdCore::table($strName).$id);
  }

}

DdItemsCacher::$force = Config::getVarVar('dd', 'forceCache');
