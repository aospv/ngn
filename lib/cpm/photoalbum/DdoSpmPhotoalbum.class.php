<?php

class DdoSpmPhotoalbum extends Ddo {
  
  protected $slaveStrName;
  protected $slavePageId;

  /**
   * @var PhotoalbumCore
   */
  public $oAlbum;
  
  protected function initTpls() {
    parent::initTpls();
    Arr::checkEmpty($this->page['settings'], 'slavePageId');
    $this->slaveStrName = DdCore::getSlaveStrName($this->page['strName']);
    $this->slavePageId = $this->page['settings']['slavePageId'];
    $this->oAlbum = new PhotoalbumCore($this->page['strName'], 'image');
    Arr::to_obj_prop($this->page['settings'], $this->oAlbum);
    $slavePage = DbModelCore::get('pages', $this->slavePageId);
    Misc::checkEmpty($slavePage);
    $this->ddddByName['preview'] =
      '`<a href="'.$slavePage['path'].
      '/v.'.DdCore::masterFieldName.'.`.$id.`" class="thumb">'.
      '<img src="`.Tt::getPath(0).`'.$this->oAlbum->imagesWpath.'/image_`.$id.`.jpg" title="`.$o->items[$id][`title`].`"></a>`';
  }
  
  public function el($value, $fieldName, $itemId) {
    $this->oAlbum->generateImage($itemId);
    return parent::el($value, $fieldName, $itemId);
  }
  
}
