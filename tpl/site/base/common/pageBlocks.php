<?php

die2('depricated');

if (!$d['blocks']) return;
PageBlockCore::sortBlocks($d['blocks'], $d['blocksSettings']['colsN']);
print '<div class="pageBlocks">';
for ($colN=1; $colN <= $d['blocksSettings']['colsN']; $colN++) {
  print '<div class="col" id="col'.$colN.'">';
  if (isset($d['blocks'][$colN])) {
    foreach ($d['blocks'][$colN] as $b) {
      print '<div class="block pbt_'.$b['type'].
      //(!empty($b['page']['name']) ? ' pageName_'.$b['page']['name'] : '').
      //(!empty($b['page']['module']) ? ' module_'.$b['page']['module'] : '').
      //(!empty($b['page']['settings']['ddItemsLayout']) ?
      //  ' ddil_'.$b['page']['settings']['ddItemsLayout'] : '').
      ' colN'.$b['colN'].'" '.
      'id="block_'.$b['id'].'">'.
      '<div class="bcont">'.
      $b['html'].
      '</div>'.
      '</div>';
    }
  }
  print '</div>';
}
print '<div class="clear"><!-- --></div>';
print '</div>';
