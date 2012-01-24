<?php

class PartialJobDdImages extends PartialJob {
  
  protected $images;
  protected $strName;
  protected $pageId;
  protected $w;
  protected $h;
  protected $type; // small/middle
  
  /**
   * @var DdItemsManager
   */
  protected $oIM;
  
  /**
   * @param string  $strName
   * @param integer $pageId
   * @param integer $w
   * @param integer $h
   * @param string  sm/md
   */
  public function __construct($strName, $pageId, $w, $h, $type) {
    $this->strName = $strName;
    $this->pageId = $pageId;
    $this->w = $w;
    $this->h = $h;
    $this->oIM = DdCore::getItemsManager($pageId);
    if ($type == 'sm') {
      $this->oIM->imageSizes['smW'] = $w;
      $this->oIM->imageSizes['smH'] = $h;
    } else {
      $this->oIM->imageSizes['mdW'] = $w;
      $this->oIM->imageSizes['mdH'] = $h;
    }
    $this->type = $type;
    foreach (array_keys($this->oIM->oForm->oFields->getFieldsByAncestor('imagePreview')) as $name) {
      foreach (db()->selectCol(
      "SELECT $name FROM ".DdCore::table($strName)." WHERE pageId=?d", $pageId) as $image) {
        if (empty($image)) continue;
        $image = UPLOAD_PATH.'/'.$image;
        if (file_exists($image)) $this->images[] = $image;
      }
    }
    parent::__construct();
  }
  
  protected function initJobs() {
    $this->jobs = $this->images;
  }
  
  protected function makeJob($n) {
    $imagePath = $this->jobs[$n];
    if ($this->type == 'sm')
      $this->oIM->makeSmallThumbs($imagePath);
    else
      $this->oIM->makeMiddleThumbs($imagePath);
  }
  
}