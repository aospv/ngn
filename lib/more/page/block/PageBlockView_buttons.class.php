<?php

class PageBlockView_buttons extends PageBlockViewAbstract {
  
  public function html() {
    $html = '';
    foreach ($this->oPBM['settings']['buttons'] as $v) {
      $html .= '<div><a href="'.$v['link'].'" class="bbtn"><span><span>'.$v['title'].'</span></span><i></i></a><div class="clear"><!-- --></div></div>';
    }
    return $html;
  }
  
}