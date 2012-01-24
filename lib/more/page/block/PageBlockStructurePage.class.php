<?php

abstract class PageBlockStructurePage extends PageBlockStructureAbstract {

  protected function initPreFields() {
    $this->preFields[] = array(
      'title' => 'Раздел',
      'name' => 'pageId',
      'type' => 'pageId',
      'required' => true
    );
  }

}
