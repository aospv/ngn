<?php

class DdoApmPhotoalbum extends DdoSpmPhotoalbum {
  
  protected function initTpls() {
    parent::initTpls();
    $this->ddddByName['title'] =
      '`<a href="`.Tt::getPath(2).`/'.$this->slavePageId.'/editContent/v.'.DdCore::masterFieldName.'.`.$id.`" class="thumb">'.
      '<img src="./'.$this->oAlbum->imagesWpath.'/image_`.$id.`.jpg" title="Зайти в альбом" class="tooltip"></a>'.
      '<div class="clear"><!-- --></div><h2>`.$v.`</h2>`';
    $this->ddddByType['author'] = '`<a href="`.Tt::getPath(1).`/users/?a=edit&id=`.$v[`id`].`">`.$v[`login`].`</a>`';
    $this->ddddByType['image'] = '$v ? `<a href="`.$v.`" target="_blank" class="thumb">'.
      '<img src="`.Misc::getFilePrefexedPath($v, `sm_`, `jpg`).`" /></a>` : ``';
    $this->ddddByType['tagsMultiselect'] = 
      'Tt::enumDddd($v, `<a href="`.Tt::getPath(5).`/t.`.$name">$title</a>`)';
    $this->ddddByType['bool'] = '`<a href="" class="iconBtn flag flag`.($v ? `On` : `Off`).` tooltip" title="`.$name.`"><i></i></a>`';
  }
  
  static public function getObjByPage($page) {
    return new self($page['strName'], $page['path'], $page['settings']);
  }
  
}
