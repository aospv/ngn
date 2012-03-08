<?php

abstract class PbsSubPagesAbstract extends PbsAbstract {

  protected function initFields() {
    $this->fields[] = array(
      'title' => 'Количество открытых уровней',
      'name' => 'openDepth',
      'type' => 'select',
      'default' => 2,
      'required' => true,
      'options' => array(1, 2, 3, 4, 5, 6)
    );
  }

}
