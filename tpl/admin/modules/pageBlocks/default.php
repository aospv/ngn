<? //Tt::tpl('admin/modules/pages/gray-header', $d) ?>
<? Tt::tpl('admin/modules/pageBlocks/header', $d) ?>

<link rel="stylesheet" type="text/css" href="./i/css/common/grid.css" media="screen, projection" />

<?php


// --------------------------------
print '<div id="blocks" class="pageBlocks">';
for ($colN=1; $colN <= $d['colsNumber']; $colN++) {
  $col = $d['cols'][$colN];
  print '<div class="col'.
        (' span-'.$col['span']).
        ($col['allowBlocks'] ? '' : ' blocksNotAllowed').
        '" id="col'.$colN.'">';

  if ($col['allowBlocks'])
    print '<div class="iconsSet"><a href="'.Tt::getPath(3).'/newBlock/'.$colN.'" class="add"><i></i> Создать блок</a></div>';      
  print '<div class="blocksBody">';      
  if (isset($d['blocks'][$colN])) {
    foreach ($d['blocks'][$colN] as $b) {
      print '<div class="block'.($b['global'] ? ' global' : '').
            ' size-'.$b['size'].' pbt_'.$b['type'].'"'.
            ' id="block_'.$b['id'].'">'.
            '<div class="editBlock smIcons bordered tooltips">'.
            (Misc::isGod() ? '<a href="" class="sm-delete" title="Удалить блок"><i></i></a>' : '').
            '<a href="'.Tt::getPath(3).'/editBlock?id='.$b['id'].'" class="sm-edit" title="Редактировать блок"><i></i></a>'.
            '</div>'.
            '<div class="clear"><!-- --></div>'.
            '<div class="bcont" '.Tt::enumInlineStyles($b['styles']).'>'.
            //($oPB->title ? '<h2>'.$oPB->title.'</h2>' : '').
            ($b['html'] ? $b['html'] : '<div class="info">Тип блока: <b>'.$b['type'].'</b><br />В текущем состоянии блок пустой.<br />Проверьте его отображение на сайте</div>').
            '</div></div>';
    }
  }
  print '</div>';  
  print '</div>';  
}
print '</div>';

?>

<div class="legend">
  <p><b>Легенда:</b></p>
  <a href=""></a>
  <div class="col"><div class="blocksBody">Колонка для размещения блоков</div></div>
  <!-- 
  <div class="block global">
    <p>Глобальный блок</p>
    <small>Располагается на всех разделах</small>
  </div>
   -->
  <div class="block pbt_duplicate">
    <p>Дубликат глобального блока</p>
    <small>Располагается на всех разделах</small>
  </div>
  <div class="block">
    <p>Обычный блок</p>
    <small>Располагается только в текущем разделе «<?= $d['page']['title'] ?>»</small>
  </div>
</div>
<script type="text/javascript">
$('body').addClass('newLayout');
new Ngn.PageBlocksEdit(<?= $d['colsNumber'] ?>);
</script>
