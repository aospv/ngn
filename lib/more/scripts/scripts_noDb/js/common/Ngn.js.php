<?php
if (DEBUG_STATIC_FILES === true)
  SFLM::storeAllJsLibs();

?>
// --------------------------Core------------------------------

Ngn.fileSizeMax = <?= Misc::phpIniFileSizeToBytes(ini_get('upload_max_filesize')) ?>;
Ngn.sessionId = '<?= session_id() ?>';
Ngn.vkApiId = <?= Arr::jsValue(Config::getVarVar('vk', 'appId', true)) ?>;
Ngn.siteTitle = '<?= SITE_TITLE ?>';
Ngn.fromVk = <?= Arr::jsValue((bool)isset($_SESSION['fromVk'])) ?>;
<? if (PageControllersCore::exists('userData')) { ?>
Ngn.tpls.userPath = '<?= Tt::getUserPath('{id}') ?>';
<? } ?>

