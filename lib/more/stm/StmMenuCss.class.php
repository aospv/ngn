<?php

class StmMenuCss {
  
  /**
   * @var StmMenuData
   */
  protected $oSMD;
  
  /**
   * @var StmThemeData
   */
  protected $oSTD;
  
  /**
   * @var StmCss
   */
  public $oCss;
  
  /**
   * @param StmThemeData Данные меню
   */
  public function __construct(StmMenuData $oSMD, StmThemeData $oSTD = null) {
    $this->oSMD = $oSMD;
    $this->oSTD = $oSTD;
    $this->oCss = new StmCss();
    $this->oCss->addAutoCss($this->oSMD, 'menu');
    $this->initDynamicCss();
  }
  
  protected function initDynamicCss() {
    if (empty($this->oSTD->data['data']['menu'])) // Если в теме не выбрано меню
      return;
    StmCss::extendImageUrls($this->oSMD);
    $this->oSMD->data['data']['menu'] = $this->oSTD->data['data']['menu'];
    $this->oCss->addCssFile(STM_PATH.'/css/menu/paddings.php', $this->oSMD);
    $this->oCss->addCssFile(
      STM_MENU_PATH.'/'.$this->oSMD->data['data']['menu'].'/css.php',
      $this->oSMD
    );
  }
  
}
