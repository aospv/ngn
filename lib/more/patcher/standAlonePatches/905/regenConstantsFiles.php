<?php

function patch_905_regenConstantsFiles($webrootFolder, $fromNgnFolder, $toNgnFolder) {

// -- File: C:\a\www\ngn/dev/lib/more/patcher/standAlonePatches/regenConstantsFiles.php

ConfigReset::rebuildConstants($webrootFolder.'/site', $toNgnFolder);
}
