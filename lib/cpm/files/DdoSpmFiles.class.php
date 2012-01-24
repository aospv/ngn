<?php

class DdoSite_m_files extends Ddo {
  
  protected function initTpls() {
    parent::initTpls();
    $this->ddddByName['title'] = 
      '`<h2><a href="`.'.$this->ddddItemLink.'.`?a=getFile&fn=file" class="ifLink" target="_blank">`.$v.` (`.File::format2($o->items[$id][`file_fSize`]).`)</a></h2>`';
  }
    
}
