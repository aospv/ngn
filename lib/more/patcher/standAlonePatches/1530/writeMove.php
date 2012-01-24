<?php

function patch_1530_writeMove($webrootFolder, $fromNgnFolder, $toNgnFolder) {

// -- File: E:\www\ngn\env/ngn/lib/more/patcher/standAlonePatches/writeMove.php

Dir::move($webrootFolder.'/site/write/logs', $webrootFolder.'/site/logs');
Dir::move($webrootFolder.'/site/write/data', $webrootFolder.'/site/data');
Dir::remove($webrootFolder.'/site/write');
}
