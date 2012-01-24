<?php

class DbPatcher extends Patcher {

  protected function initPatchesFolders() {
    // Определяем глобальную константу, потому что использование класса DbPatcher
    // возможно только при установленной NGN. 
    $this->patchesFolders[] = LIB_PATH.'/more/patcher/dbPatches';
    //if (defined('MASTER_PATH') and MASTER_PATH)
    //  $this->patchesFolders[] = MASTER_PATH.'/dbPatches';
  }

  public function getSiteLastPatchN() {
    return SiteConfig::getConstant('site', 'LAST_DB_PATCH');
  }
  
  public function updateSiteLastPatchN($n) {
    SiteConfig::updateConstant('site', 'LAST_DB_PATCH', $n);
  }

}