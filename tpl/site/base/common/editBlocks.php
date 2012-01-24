<?php

/*
if (!($blockSettings = Config::getVar('pageBlocks_'.$d['page']['id'], true))) {
  $blockSettings['colsN'] = 3;
}
$blockSettings['mr'] = 10;
print '<link rel="stylesheet" type="text/css" href="./s2/css/common/pageBlocks.css?'.
  http_build_query($blockSettings).'" media="screen, projection" />';
*/

$pageBlocksConf = PageBlocksCssSettings::get($d['page']['id']);
// margin вместо 1px бордюров:
$pageBlocksConf['mr'] = 6;
$pageBlocksConf['mb'] = 3;

//$pageBlocksConf['bv'] = $pageBlocksConf['bh'] = 2;
print '<link rel="stylesheet" type="text/css" href="./s2/css/common/pageBlocks?'.
  http_build_query($pageBlocksConf).
  '" media="screen, projection" />';

PageBlockCore::sortBlocks($d['blocks'], $pageBlocksConf['colsN']);
print '<div id="blocks" class="pageBlocks">';
for ($colN=1; $colN <= $pageBlocksConf['colsN']; $colN++) {
  print '<div class="col" id="col'.$colN.'">';
  if (isset($d['blocks'][$colN])) {
    foreach ($d['blocks'][$colN] as $b) {
      print '<div class="block id="block_'.$b['id'].'">'.
            //'<span class="gray">['.$oPB->id.']</span>'.
            '<span class="iconsSet">'.
            '<a href="" class="delete gray"><i></i> удалить</a>'.
            '<a href="'.Tt::getPath().'?a=editBlock&id='.$b['id'].'" class="edit gray"><i></i> редактировать</a>'.
            '</span>'.
            '<div class="clear"><!-- --></div>'.
            '<div class="bcont">'.
            //($oPB->title ? '<h2>'.$oPB->title.'</h2>' : '').
            $b['html'].
            '</div></div>';
    }
  }
  print '</div>';  
}
print '</div>';

?>

<script type="text/javascript">
new Ngn.PageBlocksEdit(<?= $pageBlocksConf['colsN'] ?>);
</script>
