<?php

$file = Misc::getScriptPath('s2/css/common/tiny.pageBlocks.css');
$selector = '.pageBlocks';
if (!empty($_REQUEST['blockType'])) $selector .= ' .pbt_'.$_REQUEST['blockType'];
print str_replace(' body ', ' ', 
  CssCore::wrapSelectors(file_get_contents($file), $selector));