<?php

abstract class PageBlockViewAbstract {

  static public $cachable = false;

  /**
   * @var DbModel
   */
  protected $oPBM;
  
  /**
   * Current Controller
   * 
   * @var CtrlPage
   */
  public $oCC;
  
  protected $cssClass = '';
  
  public function __construct(DbModel $oPBM, $oCC = null) {
    $this->oPBM = $oPBM;
    $this->oCC = $oCC;
    $this->init();
  }
  
  protected function init() {
  }
  
  public function styles() {
    return array();
  }
  
  abstract public function html();
  
  /**
   * Возвращает готовые данные для отображения блока (html и стили)
   */
  public function getData() {
    return array(
      'id' => $this->oPBM['id'],
      'type' => $this->oPBM['type'],
      'colN' => $this->oPBM['colN'],
      'html' => $this->html(),
      'class' => $this->cssClass,
      'styles' => $this->styles()
    );
  }

}