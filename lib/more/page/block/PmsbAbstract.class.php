<?php

/**
 * Page module static blocks
 */
abstract class PmsbAbstract {

  /**
   * CtrlPage
   */
  protected $ctrl;
  
  protected $name;
  
  public $blocks = array();
  
  public $module;
  
  public function __construct(CtrlPage $ctrl) {
    $this->ctrl = $ctrl;
    $this->name = ClassCore::classToName('Pmsb', get_class($this));
    $this->initBlocks();
  }

  /*
  protected function getName() {
    return lcfirst(Misc::removePrefix('Pmsb', get_class($this)));
  }
  
  protected function getTplName() {
    return 'pmsb/'.$this->getName();
  }
  */
  
  abstract protected function initBlocks();
  
  public function blocks() {
    return empty($this->blocks) ? false : $this->blocks;
  }
  
  protected function addBlock(array $data) {
    $data['className'] = $this->name;
    Misc::checkEmpty($data['type']);
    $this->blocks[] = $data;
  }
  
  public function processDynamicBlockModels(array &$blockModels) {}

}
