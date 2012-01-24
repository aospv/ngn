<?php

class PhotoalbumCore {

  const slaveImageFieldName = 'image';
  
  protected $masterStrName;
  protected $imageField;
  
  /**
   * @var ImageComposer
   */
  public $oIC;
  
  public $mozaicW = 300;
  public $mozaicH = 400;
  public $mozaicElW = 30;
  public $mozaicElH = 15;
  public $imagesPath;
  public $imagesWpath;
  
  public function __construct($masterStrName) {
    $this->masterStrName = $masterStrName;
    $this->oIC = new ImageComposer();
    $this->imagesPath = UPLOAD_PATH.'/albums/'.$this->masterStrName;
    $this->imagesWpath = UPLOAD_DIR.'/albums/'.$this->masterStrName;
    Dir::make($this->imagesPath);
  }
  
  public function generateImage($id) {
    if (!$this->imageExists($id)) $this->_generateImage($id);
  }
  
  public function deleteImage($id) {
    unlink($this->getImageFile(WEBROOT_PATH.'/'.$id));
  }
  
  private function imageExists($id) {
    return file_exists($this->getImageFile($id));
  }
  
  private function getImageFile($id) {
    return $this->imagesPath.'/'.self::slaveImageFieldName.'_'.$id.'.jpg';
  }
  
  private function _generateImage($id) {
    $this->oIC->mozaicW = $this->mozaicW;
    $this->oIC->mozaicH = $this->mozaicH;
    $file = $this->getImageFile($id);
    $images = db()->selectCol(
      "SELECT ".self::slaveImageFieldName." FROM ".
      DdCore::table(DdCore::getSlaveStrName($this->masterStrName))." WHERE ".
      DdCore::masterFieldName."=?d AND active=1", $id);
    if (!$images) {
      $images = array(NGN_PATH.'/i/img/no-images.gif');
    } else {
      foreach ($images as &$image) $image = UPLOAD_PATH.'/'.$image;
    }
    $this->oIC->mozaicSplitFiles = false;
    $this->oIC->_mosaic($this->mozaicElW, $this->mozaicElH, $images, $file);
    return $file;
  }
  
  // -----------------------------------------------------------------------------------

  /**
   * Удаляет мозики альбомов.
   * Используется в том случае, если мозаику альбома нужно обновить
   *
   * @param string $strName
   * @param integer $id
   */
  public function deleteImages($albumItemId) {
    foreach (glob($this->imagesPath.'/*_'.$albumItemId.'.jpg') as $filename) {
      unlink($filename);
    }
  }
  
  public function cleanup() {
    Dir::remove($this->imagesPath);
    NgnCache::clean();
  }
  
}
