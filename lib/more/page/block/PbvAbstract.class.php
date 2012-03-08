<?php

/**
 * Page Block View
 */
abstract class PbvAbstract {

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
  
  protected $extendImageData = false;
  
  public function __construct(DbModel $oPBM, $oCC = null) {
    $this->oPBM = $oPBM;
    $this->extendImageData();
    $this->oCC = $oCC;
    $this->init();
    $this->initButtons();
  }
  
  protected function initButtons() {}
  
  protected function extendImageData() {
    if (!$this->extendImageData) return;
    $this->oPBM['settings'] = DataManagerAbstract::extendImageData(
      $this->oPBM['settings'],
      PageBlockCore::getStructure($this->oPBM['type'])->getFields()
    );
  }
  
  protected function init() {
  }
  
  public function styles() {
    return array();
  }
  
  public function _html() {
    return Tt::getTpl('pageBlocks/'.ClassCore::classToName('Pbv', get_class($this)), $this->oPBM['settings']);
  }
  
  protected $moreLink;
  protected $buttons = array();
  
  public function html() {
    $titleHtml = '';
    if (isset($this->moreLink))
      $titleHtml .= '<a href="'.$this->moreLink['link'].'" class="hbtn small"><span>'.$this->moreLink['title'].'</span></a>';
    if ($this->buttons) {
      $titleHtml .= '<div class="smIcons bordered">';
      foreach ($this->buttons as $v) {
        $titleHtml .= '<a href="'.$v['link'].'" title="'.$v['title'].'" class="sm-'.$v['class'].'"><i></i></a>';
      }
      $titleHtml .= '</div>';
    }
    $html = '';
    if ($titleHtml or !empty($this->oPBM['settings']['title'])) {
      $html .=
        '<div class="btitle">'.
        $titleHtml.
        ($this->oPBM['settings']['title'] ? '<h2>'.$this->oPBM['settings']['title'].'</h2>' : '').
        '</div>';
    }
    $html .= '<div class="bbody">'.$this->_html().'</div>';
    return $html;
  }
  
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