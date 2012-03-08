<?php

class PbvLastComments extends PbvAbstract {
  
  /**
   * @var CommentsCollection
   */
  public $comments;
  
  protected function init() {
    $this->comments = Comments::getLast(!empty($this->oPBM['settings']['limit']) ?
      $this->oPBM['settings']['limit'] : 5);
  }
  
  public function _html() {
    return
      ($this->oPBM['settings']['title'] ? '<h2>'.$this->oPBM['settings']['title'].'</h2>' : ''). 
      Tt::getTpl(
        'common/lastComments',
        $this->comments->getItems()
      );
  }
  
}