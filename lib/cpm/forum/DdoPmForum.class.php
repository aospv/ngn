<?php

class DdoPmForum extends Ddo {
  
  protected function init() {
    parent::init();
    $this->ddddByName['text'] = '<div class="bcont">`.$v.`</div>';
  }
  
}