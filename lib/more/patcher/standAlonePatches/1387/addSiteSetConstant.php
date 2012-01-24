<?php

function patch_1387_addSiteSetConstant($webrootFolder, $fromNgnFolder, $toNgnFolder) {

// -- File: E:\www\ngn\env/ngn/lib/more/patcher/standAlonePatches/addSiteSetConstant.php

$file = $webrootFolder.'/site/config/constants/site.php';
if (!Config::getConstant($file, 'SITE_SET')) {
	Config::replaceConstant($file, 'SITE_SET', 'personal');
}
}
