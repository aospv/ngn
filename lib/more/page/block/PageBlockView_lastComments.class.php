<?php

class PageBlockView_lastComments extends PageBlockViewAbstract {
  
  public function html() {
    return
      //(($path = Tt::getControllerPath('comments', true)) ?
      //  '<a href="'.$path.'" class="btn btn2"><span>Все комментарии</span></a>' : '').
      ($this->oPBM['settings']['title'] ? '<h2>'.$this->oPBM['settings']['title'].'</h2>' : ''). 
      Tt::getTpl(
        'common/lastComments',
        Comments::getLast(!empty($this->oPBM['settings']['limit']) ?
          $this->oPBM['settings']['limit'] : 5)
      );
  }
  
}