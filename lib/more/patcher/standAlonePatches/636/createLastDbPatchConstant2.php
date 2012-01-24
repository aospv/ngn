<?php

function patch_createLastDbPatchConstant2($webrootFolder, $fromNgnFolder, $toNgnFolder) {

# -- File: C:/a/www/ngn/dev/lib/more/patcher/standAlonePatches/createLastDbPatchConstant2.php

  $oP = new StandAloneDbPatcher();
  $oP->noCache = true;
  $oP->setPatchesFolder($toNgnFolder.'/lib/more/patcher/dbPatches');
  $oP->setSiteFolder($webrootFolder.'/site');
  $oP->updateSiteLastPatchNFromNgn();
}
