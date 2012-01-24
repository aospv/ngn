<?php

class StmMenuData extends StmData {

  /**
   * @var StmMenuDataSource
   */
  public $oSDS;
  
  public $name = 'menu';
  
  protected $requiredOptions = array('id');
  
  public $menuType;

  public function __construct(StmDataSource $oSDS, array $options) {
    parent::__construct($oSDS, $options);
    $o = new StmThemeData($oSDS, $this->options);
    Arr::checkEmpty($o->data['data'], 'menu');
    $this->menuType = $o->data['data']['menu'];
    $this->data['siteSet'] = $o->data['siteSet'];
    $this->data['design'] = $o->data['design'];
  }

  public function getStructure() {
    return StmCore::getMenuStructure($this->menuType);
  }

}
