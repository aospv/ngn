<?php

class CtrlPageDdItem extends CtrlPageDdStatic {
  
  protected function getStaticId() {
    return $this->page['id'];
  }
  
  public function action_new() {
    if (($id = parent::action_new()) !== false) {
      if ($this->oManager->data['title']) {
        PagesAdmin::_update($this->page['id'], array(
          'title' => $this->oManager->data['title'],
        ));
      }
    }
  }
  
  public function action_edit() {
    if (!parent::action_edit()) return false;
    $oPages = new PagesAdmin();
    $oPages->updateTitle($this->page['id'], $this->oManager->data['title']);
    return true;
  }
  
}
