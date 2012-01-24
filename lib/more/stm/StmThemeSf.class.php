<?php

/**
 * Base class for theme static files (js, css) generators
 */
abstract class StmThemeSf {

  /**
   * @var StmThemeData
   */
  protected $oSD;

  public function __construct(StmThemeData $oSD) {
    $this->oSD = $oSD;
    $this->init();
  }

  abstract protected function init();
  
  /**
   * @return StmMenuData
   */
  protected function getMenuData() {
    if (empty($this->oSD->data['data']['menu'])) return false;
    return O::get('StmMenuData', $this->oSD->oSDS, array(
      'id' => $this->oSD->id,
      'siteSet' => $this->oSD->data['siteSet'],
      'design' => $this->oSD->data['design']
    ));
  }
  
}