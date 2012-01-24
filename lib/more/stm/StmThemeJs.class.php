<?php

class StmThemeJs extends StmThemeSf {

  public $js = '';
  
  protected function init() {
    $this->initHomeOffset();
    $this->initMenu();
  }
  
  protected function initHomeOffset() {
    if (empty($this->oSD->data['data']['homeTopOffset'])) return;
    $y = (int)$this->oSD->data['data']['homeTopOffset'];
    $this->js .= "
if (!Ngn.layout) Ngn.layout = {};
Ngn.layout.homeTopOffset = $y;
";
  }
  
  protected function initMenu() {
    if (($oMD = $this->getMenuData()) === false) return;
    if (!file_exists(STM_MENU_PATH.'/'.$oMD->menuType.'/js.php')) return;
    $this->js .= Misc::getIncluded(STM_MENU_PATH.'/'.$oMD->menuType.'/js.php', $oMD->data['data']);
    $oThemeCss = new StmThemeCss($this->oSD);
    if ($oThemeCss->oMenuCss) $this->js .= CssCore::getProloadJs($oThemeCss->oMenuCss->oCss->css);
  }

}
