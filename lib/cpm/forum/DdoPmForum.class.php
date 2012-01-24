<?php

class DdoPmForum extends Ddo {
  
  protected function init() {
    parent::init();
    $this->ddddByName['text'] = 
      '`<div class="roundCorners top"><i class="l"></i><i class="r"></i><div class="clear"></div></div>'.
      '<div class="bcont">`.$v.`</div>'.
      '<div class="roundCorners bottom"><i class="l"></i><i class="r"></i><div class="clear"></div></div>`';
  }
  
}