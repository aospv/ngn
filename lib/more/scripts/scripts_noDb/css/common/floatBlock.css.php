<?php

$r = array();
$smW = Config::getVarVar('dd', 'smW');
foreach (DbModelCore::collection('pages', DbCond::get()->addNullFilter('strName', false)) as $page) {
  if (!PageControllersCore::hasAncestor($page['controller'], 'items')) continue;
  $layoutN = PageLayoutN::get($page->id);
  $cWidth = PageLayout::getContentColWidth($page->id);
  if (!empty($page->settings['smW']))
    $r['custom'][$layoutN][$page->name] = $cWidth - $page->settings['smW'] - 15;
  else
    $r['default'][$layoutN] = $cWidth - $smW - 15;
}

foreach ($r['default'] as $layoutN => $w) {
  print ".layout_$layoutN .ddItems .hgrpt_floatBlock { width: {$w}px; }\n";
}
if (!empty($r['custom'])) {
  foreach ($r['custom'] as $layoutN => $values) {
    foreach ($values as $pageName => $w) {
      print ".layout_$layoutN.pageName_$pageName .ddItems .hgrpt_floatBlock { width: {$w}px; }\n";
    }
  }
}
