<?php

class StmDesigns extends Options2 {

  public $designs;
  protected $requiredOptions = array('siteSet');
  
  protected function init() {
    foreach (Dir::dirs(STM_DESIGN_PATH.'/'.$this->options['siteSet']) as $folder) {
      $this->designs[$folder] = 
        include STM_DESIGN_PATH.'/'.$this->options['siteSet'].
          '/'.$folder.'/structure.php';
    }
  }

}