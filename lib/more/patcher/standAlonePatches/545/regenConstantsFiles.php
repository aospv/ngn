<?php

// @todo исправить шаблон для создания таких ф-ий
//       добавить в него ($webrootFolder, $fromNgnFolder, $toNgnFolder)
function patch_regenConstantsFiles($webrootFolder, $fromNgnFolder, $toNgnFolder) {
  
  # -- File: C:/a/www/ngn/dev/lib/more/patcher/patches/regenConstantsFiles.php
  ConfigReset::rebuildConstants($webrootFolder.'/site', $toNgnFolder);
}
