<?php

class DmfaWisiwigAbstract extends Dmfa {
  
  public function source2formFormat($v) {
    return htmlspecialchars($v);
  }

}