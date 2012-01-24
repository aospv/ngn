<?php

class PageBlockSettingsForm extends Form {

  public function __construct(PageBlockTBase $oPB) {
    parent::__construct(new Fields($oPB->getFields()));
  }
  
}