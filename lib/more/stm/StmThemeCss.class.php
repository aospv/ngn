<?php

class StmThemeCss extends StmThemeSf {
  
  /**
   * В этом файле описываются стили, использующие данные $this->oSD->data['data'] для текущего дизайна
   * 
   * @var string
   */
  protected $designCssFile;
  
  /**
   * @var StmCss
   */
  public $oCss;
  
  protected $sizes;
  
  protected $data;
  
  protected function init() {
    $this->oCss = new StmCss();
    $this->designCssFile = STM_DESIGN_PATH.'/'.
      $this->oSD->data['siteSet'].'/'.$this->oSD->data['design'].'/css.php';
    $this->extendImageUrls();
    $this->initDynamicCss();
    $this->initAutoCss();
    $this->initStaticCss();
    $this->initMenuCss();
  }
  
  protected function extendImageUrls() {
    StmCss::extendImageUrls($this->oSD);
  }
  
  protected function initAutoCss() {
    if (empty($this->oSD->data['cssData'])) return;
    $this->oCss->addAutoCss($this->oSD, 'design');
  }
  
  protected function initDynamicCss() {
    if (!file_exists($this->designCssFile)) {
      $this->oCss->addNoFileComment($this->designCssFile);
      return;
    } else {
      $this->oCss->addCssFile($this->designCssFile, $this->oSD);
    }
    $this->oCss->addCssFile(STM_PATH.'/css/pageBlocks/subPages.php', $this->oSD);
    $this->oCss->addCssFile(STM_PATH.'/css/content.php', $this->oSD);
    $this->oCss->addCssFile(STM_PATH.'/css/slices.php', $this->oSD);
    $this->oCss->addCssFile(STM_PATH.'/css/buttons.php', $this->oSD);
    return;
    $this->oCss->addCssFile(STM_PATH.'/css/layout.php', $this->oSD);  // колонки
    
  }
  
  protected function addStaticCssFile($file) {
    $this->oCss->addHeaderComments($file);
    $this->oCss->css .= Misc::getIncluded($file);
  }
  
  protected function initStaticCss() {
    if ($this->oSD->data['data']['black']) {
      $this->addStaticCssFile(STATIC_PATH.'/css/black/icons.css');
      $this->addStaticCssFile(STATIC_PATH.'/css/black/dialog.css');
    }
    $this->addStaticCssFile(STATIC_PATH.'/css/pageBlocks/subPages.css');
  }
  
  /**
   * @var StmMenuCss
   */
  public $oMenuCss;
  
  protected function initMenuCss() {
    if (($oMD = $this->getMenuData()) === false) return;
    $this->oMenuCss = O::get('StmMenuCss', $oMD, $this->oSD);
    $this->oCss->css .= $this->oMenuCss->oCss->css;
  }
  
}
