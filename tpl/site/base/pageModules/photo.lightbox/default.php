<style>
.items .item {
position: relative;
width: <?= $d['page']['settings']['smW'] ?>px;
float: left;
}
</style>

<script type="text/javascript">
window.addEvent('domready', function(){
  new Lightbox({
    assetBaseUrl: './i/css/common',
    original: function(link) { return link.replace('md_', ''); }
  }, $$('a.thumb'));
});  
</script>

<?

print Slice::html(
  'beforeDdItems_'.$d['page']['id'],
  'Блок над фотографиями «'.$d['page']['title'].'»'
);

/* @var $oDdo Ddo */
$oDdo = O::get('Ddo',
  $d['page'], 'siteItems'
)->setItems($d['items']);

$oDdo->ddddByType['image'] = 
  '$v ? `<a href="`.Misc::getFilePrefexedPath($v, `md_`, `jpg`).`" class="thumb" rel="ngnLightbox[set1]">'.
  '<img src="`.Misc::getFilePrefexedPath($v, `sm_`, `jpg`).`" class="tooltip" title="`.$o->items[$id][`title`].`" alt="`.$o->items[$id][`title`].`" /></a>` : ``';

$oDdo->canEdit = false;

if (!empty($d['items'])) {
  print $oDdo->els();
  print '<div class="clear"><!-- --></div>';
} else {
  print Slice::html('noItems', 'Нет фото');
}

print Slice::html(
  'afterDdItems_'.$d['page']['id'],
  'Блок под фотографиями «'.$d['page']['title'].'»'
);
