<?php

class StmThemeData extends StmData {

  /**
   * @var StmThemeDataSource
   */
  public $oSDS;
  
  public $name = 'theme';
  
  public function __construct(StmDataSource $oSDS, array $options) {
    parent::__construct($oSDS, $options);
  }
  
  public function getStructure() {
    if (empty($this->data['siteSet']) or empty($this->data['design']))
      throw new NgnException('Wrong data ID='.$this->id.': '.getPrr($this->data));
    return StmCore::getThemeStructure($this->data['siteSet'], $this->data['design']);
  }

}
