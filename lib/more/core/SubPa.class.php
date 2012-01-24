<?php

abstract class SubPa {
  
  /**
   * @var CtrlPage
   */
  protected $oPA;
  
  public $disable = false;
  
  public function __construct(CtrlCommon $oPA) {
    $this->oPA = $oPA;
  }
  
  public function getName() {
    return lcfirst(Misc::removePrefix('SubPa', get_called_class()));
  }
  
  public function init() {}

}
