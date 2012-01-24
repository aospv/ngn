<?php

function patch_946_updateSiteDomain($webrootFolder, $fromNgnFolder, $toNgnFolder) {

// -- File: C:\a\www-ngn/ngn/lib/more/patcher/standAlonePatches/updateSiteDomain.php

Config::updateConstant($webrootFolder.'/site/config/constants/site.php', 'SITE_DOMAIN', $_SERVER['HTTP_HOST']);
}
