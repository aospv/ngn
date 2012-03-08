<?php

abstract class PbsPage extends PbsAbstract {

  protected function initPreFields() {
    $this->preFields[] = array(
      'title' => 'Раздел',
      'name' => 'pageId',
      'type' => 'pageId',
      'required' => true
    );
  }

}
