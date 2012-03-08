<?php

class PbvIconLink extends PbvAbstract {
  
  static public $cachable = true;

  public function styles() {
    $s = getimagesize(UPLOAD_PATH.'/'.$this->oPBM['settings']['image']);
    return array(
      'background-image' => 'url('.UPLOAD_DIR.'/'.$this->oPBM['settings']['image'].')',
      'padding-left' => ($s[0]+5).'px',
      'min-height' => $s[1].'px'
    );
  }

  public function _html() {
    return 
      '<h2><a href="'.$this->oPBM['settings']['url'].'">'.
      $this->oPBM['settings']['title'].'</a></h2>'.
      $this->oPBM['settings']['text'];
  }

}