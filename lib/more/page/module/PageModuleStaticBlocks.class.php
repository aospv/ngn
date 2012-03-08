<?php

class PageModuleStaticBlocks {

  public $blocks = array();
  protected $blocksObjs = array();

  public function __construct(CtrlPage $oController) {
    if (empty($oController->page['module'])) return false;
    if (($info = PageModuleCore::getInfo($oController->page['module'])) === false) return;
    $info = $info->getData('blocks');
    if (!empty($info['disableModuleParentBlocks'])) {
      if (($class = PageModuleCore::getClass($oController->page['module'], 'Pmsb')) !== false) {
        $this->blocksObjs[] = O::get($class, $oController);
      }
    } else {
      foreach (PageModuleCore::getAncestorClasses($oController->page['module'], 'Pmsb') as $class) {
        $this->blocksObjs[] = O::get($class, $oController);
      }
    }
    foreach ($this->blocksObjs as $o)
      if (($blocks = $o->blocks()) !== false)
        $this->blocks = array_merge($this->blocks, $blocks);
  }
  
  public function processDynamicBlockModels(array &$blockModels) {
    foreach ($this->blocksObjs as $o)
      $o->processDynamicBlockModels($blockModels);
  }

}