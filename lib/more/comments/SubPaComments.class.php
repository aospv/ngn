<?php

class SubPaComments extends SubPaMsgs {
  
  protected function initMsgs() {
    $this->oMsgs = new Comments($this->id1, $this->id2);
  }
  
}