<?php

class PageBlockView_iconLinks extends PageBlockViewAbstract {
  
  static public $cachable = false;

  public function html() {
    $table = array();
    foreach ($this->oPBM['settings']['items'] as $v) {
      $table[] = array(
        '<img src="'.UPLOAD_DIR.'/'.$v['image'].'" />',
        '<a href="'.$v['url'].'">'.$v['text'].'</a>',
      );
    }
    return
      ($this->oPBM['settings']['title'] ?
        '<h2>'.$this->oPBM['settings']['title'].'</h2>' : '').
      Tt::getTpl('common/table', $table);
  }

}