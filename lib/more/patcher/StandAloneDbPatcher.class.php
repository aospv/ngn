<?php

class StandAloneDbPatcher extends Patcher {
  
  private $siteFolder;
  
  public function setSiteFolder($folder) {
    $this->siteFolder = $folder;
  }
  
  protected function initPatchesFolder() {
    // dummy
  }
  
  public function getSiteLastPatchN() {
    return Config::getConstant($this->siteFolder.'/config/constants/more.php', 'LAST_DB_PATCH');
  }
  
  public function updateSiteLastPatchN($n) {
    Config::replaceConstant($this->siteFolder.'/config/constants/more.php', 'LAST_DB_PATCH', $n);
  }
  
} 
