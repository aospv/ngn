<?php

function patch_1511_regenConstantsFiles($webrootFolder, $fromNgnFolder, $toNgnFolder) {

// -- File: E:\www\ngn\env/ngn/lib/more/patcher/standAlonePatches/regenConstantsFiles.php

ConfigReset::rebuildConstants($webrootFolder.'/site', $toNgnFolder);
}
