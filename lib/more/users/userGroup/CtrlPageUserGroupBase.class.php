<?php

class CtrlPageUserGroupBase extends CtrlPage {

  static public function getVirtualPage() {
    return array(
      'title' => 'Группа'
    );
  }

  protected function init() {
    parent::init();
    if (!$this->userGroup) throw new EmptyException('$this->userGroup');
  }

}