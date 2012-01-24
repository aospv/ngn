<?

// @title Страница одной записи  

$v = $d['item'];
$oDdo = DdoSiteFactory::get($d['page'], 'siteItem')->setItem($v);
$oDdo->ddddByType['wisiwig'] = $oDdo->ddddByType['wisiwigSimple'] = '$v';
$oDdo->ddddByType['image'] = '$v ? `<a href="`.$v.`" target="_blank" class="thumb"><img src="`.Misc::getFilePrefexedPath($v, `md_`, `jpg`).`" /></a><div class="clear"><!-- --></div>` : ``';
if ($d['settings']['setItemsOnItem'] and ($d['page']['module'] == 'photo' or $d['page']['module'] == 'photoalbum_slave')) {
  list($prevId, $nextId) = Arr::proximity(array_keys($d['items']), $d['item']['id'], true);
  $oDdo->ddddByType['image'] = '$v ? `
  '.($prevId >= 0 ? '<a href="'.Tt::getPath(1).'/'.$prevId.'" class="btn btn1">&nbsp;« Предыдущая&nbsp;</a>&nbsp' : '').'
  '.($nextId >= 0 ? '<a href="'.Tt::getPath(1).'/'.$nextId.'" class="btn btn1">&nbsp;Следующая »&nbsp;</a>' : '').'
  <div class="clear"><!-- --></div>
  <a href="`.$v.`" target="_blank" class="thumb"><img src="`.Misc::getFilePrefexedPath($v, `md_`, `jpg`).`" /></a><div class="clear"><!-- --></div>` : ``';
} else
  $oDdo->ddddByType['image'] = '$v ? `<a href="`.$v.`" target="_blank" class="thumb"><img src="`.Misc::getFilePrefexedPath($v, `md_`, `jpg`).`" /></a><div class="clear"><!-- --></div>` : ``';
$oDdo->ddddByName['title'] = '';
$oDdo->ddddItemsBegin = '`<div class="element n_fieldName t_fieldType">`';

?>

<div class="contentBody<?= $d['action'] == 'showItem' ? ' oneItem' : '' ?> str_<?= $d['page']['strName'] ?>">
  <?= $oDdo->els() ?>
</div>

<?

Tt::tpl('dd/beforeComments', $d, true);

if ($d['showCommentsAfterItem'] and isset($d['oController']->subControllers['comments'])) {
  Tt::tpl(
    $d['oController']->subControllers['comments']->d['tpl'],
    $d['oController']->subControllers['comments']->d
  );
}

?>