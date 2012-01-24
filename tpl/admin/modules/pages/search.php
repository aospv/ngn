<div class="searchBlock">
  <input type="text" id="<?= $d['name'] ?>Mask" name="<?= $d['name'] ?>Search" class="mask" />
  <a href="#" id="<?= $d['name'] ?>SearchBtn" class="searchBtn"></a>
  <div class="clear"><!-- --></div>
  <div id="<?= $d['name'] ?>Results" class="results"></div>
</div>
<div class="preloadLoaderImage"></div>
<div class="clear"><!-- --></div>

<script type="text/javascript" src="./i/js/common/setSearch.js"></script>
<script type="text/javascript">
setSearch(
  '<?= Tt::getPath() ?>',
  $('<?= $d['name'] ?>SearchBtn'),
  $('<?= $d['name'] ?>Results'),
  $('<?= $d['name'] ?>Mask'),
  'ajax_<?= $d['name'] ?>Search',
);
</script>
