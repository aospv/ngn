<?php

abstract class StmData extends Options {
  
  public $data;
  public $canEdit;
  public $id;
  public $name = 'data';
  
  /**
   * @var StmDataSource
   */
  public $oSDS;
  
  public function __construct(StmDataSource $oSDS, array $options) {
    $this->oSDS = $oSDS;
    if (empty($options['new'])) {
      Arr::checkEmpty($options, 'id');
      $this->id = (int)$options['id'];
      $this->data = $this->getInitData();
    } else {
      $this->id = $this->oSDS->getNextN();
    }
    $this->setOptions($options);
  }
  
  public function getThemePath() {
    return $this->oSDS->getDataPath().'/'.$this->id;
  }
  
  public function getThemeWpath() {
    return $this->oSDS->getDataWpath().'/'.$this->id;
  }
  
  protected function getFile() {
    return $this->getThemePath().'/'.$this->name.'.php';
  }
  
  protected function getInitData() {
    $file = $this->getFile();
    File::checkExists($file);
    $r = include $file;
    return $r;
  }
  
  public function setData(array $data) {
    $this->data = $data;
    return $this;
  }
  
  public function setDataValue($k, $v) {
    $this->data['data'][$k] = $v;
    return $this;
  }
  
  public function setCssCataValue($k, $v) {
    $this->data['cssData'][$k] = $v;
    return $this;
  }
  
  public function save() {
    if (!$this->canEdit and !Misc::isGod())
      throw new NgnException('not allowed to create or change common theme data');
    Config::updateVar($this->getFile(), $this->data, true);
    return $this;
  }
  
  public function delete() {
    File::delete($this->getFile());
  }
  
  public function getName() {
    if (empty($this->name)) throw new EmptyException('$this->name');
    return $this->name;
  }
  
  abstract public function getStructure();

}

