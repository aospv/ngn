<?php

function patch_836_regenConstantsFiles($webrootFolder, $fromNgnFolder, $toNgnFolder) {

// -- File: C:\a\www\ngn/dev/lib/more/patcher/standAlonePatches/regenConstantsFiles.php

ConfigReset::rebuildConstants($webrootFolder.'/site', $toNgnFolder);
}
