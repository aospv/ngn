<?php

class DdoProfileList extends Ddo {
  
  protected function initTpls() {
    parent::initTpls();
    $this->ddddByType['num'] = '$v == 0 ? `` : `<b class="title">`.$title.`</b>: `.$v';
    $this->ddddByType['image'] = 
      '`<a href="`.Tt::getUserPath($authorId).`" class="thumb">'.
      '<img src="`.(!empty($v) ? Misc::getFilePrefexedPath($v, `sm_`, `jpg`) : `./m/img/no-avatar.gif`).`" '.
      'title="`.$authorLogin.`" alt="`.$authorLogin.`" /></a>`';
    $this->ddddByName['name'] = 
      '`<h2><a href="`.Tt::getUserPath($authorId).`#bmt_`.$pagePath.`">`.($v ? $v : $authorLogin).`</a></h2>`';
  }
  
}