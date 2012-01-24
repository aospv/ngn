<?php

class StmDataSource {

  public $location;
  protected $dataPaths;
  protected $dataWpaths;
  protected $folderName = 'themes';
  
  /**
   * Соответствия имен структуры именам стилей
   * @var array
   */
  public $strNameToCssName;
  
  public function __construct($location) {
    if (empty($location)) throw new EmptyException('$location');
    $this->location = $location;
    $this->initDataPaths();
    $this->initDataWpaths();
  }
  
  /**
   * Определяются пути в папкам темы. В зависимости от пути к теме
   * 
   * @see StmDataSource::initDataPaths()
   */
  protected function initDataPaths() {
    $this->dataPaths['ngn'] = STM_PATH.'/'.$this->folderName;
    $this->dataPaths['site'] = UPLOAD_PATH.'/'.$this->folderName;
  }
  
  protected function initDataWpaths() {
    $this->dataWpaths['ngn'] = STM_WPATH.'/'.$this->folderName;
    $this->dataWpaths['site'] = UPLOAD_DIR.'/'.$this->folderName;
  }
  
  public function getNextN() {
    Dir::make($this->getDataPath());
    if (($last = Arr::last(Dir::dirs($this->getDataPath()))) !== false)
      return str_replace('.php', '', $last+1);
    return 1;
  }
  
  public function getDataPath() {
    Arr::checkEmpty($this->dataPaths, $this->location);
    return $this->dataPaths[$this->location];
  }
  
  public function getDataWpath() {
    Arr::checkEmpty($this->dataWpaths, $this->location);
    return $this->dataWpaths[$this->location];
  }
  
}