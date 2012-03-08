<?php

class StmThemeJs extends StmThemeSf {

  public $js = '';
  
  protected function init() {
    $this->initHomeOffset();
    $this->initMenu();
    $this->initCart();
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
  
  protected function initCart() {
    if (empty($this->oSD->data['data']['slices']) or
      Arr::getValueByKey($this->oSD->data['data']['slices'], 'id', 'cart') === false) return;
    $opt = Arr::jsObj(array(
      'storeOrderController' => StoreCore::getOrderController()
    ));
    $this->js .= "
window.addEvent('domready',function() {
  Ngn.cart.initBlock($('slice_cart').getElement('.slice-text'), $opt);
});
";
  }
  
}