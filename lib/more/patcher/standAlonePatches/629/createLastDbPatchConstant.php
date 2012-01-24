<?php

function patch_createLastDbPatchConstant($webrootFolder, $fromNgnFolder, $toNgnFolder) {

# -- File: C:/a/www/ngn/dev/lib/more/patcher/standAlonePatches/createLastDbPatchConstant.php

  $oP = new StandAloneDbPatcher();
  $oP->noCache = true;
  $oP->patchesFolders = 
    array($toNgnFolder.'/lib/more/patcher/dbPatches');
  $oP->setSiteFolder($webrootFolder.'/site');
  $oP->updateSiteLastPatchNFromNgn();
}
