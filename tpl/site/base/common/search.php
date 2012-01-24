<?php
/*

Пример входных данных шаблона:
array(
  'name' => 'name',
  'actionKey' => 'user', // action=json_user_Search
  'default' => 'masted'
) 

*/
?>
<div class="searchBlock">
  <input type="text" id="<?= $d['name'] ?>Mask" name="<?= $d['name'] ?>Search" class="mask" />
  <a href="#" id="<?= $d['name'] ?>SearchBtn" class="searchBtn" title="Искать"></a>
  <a href="#" id="<?= $d['name'] ?>SearchAllBtn" class="searchBtn searchAllBtn" title="Искать всё"></a>
  <div id="<?= $d['name'] ?>Results" class="results"></div>
  <div class="preloadLoaderImage"></div>
  <? if (!$d['noClear']) { ?><div class="clear"><!-- --></div><? } ?>
</div>

<script type="text/javascript" src="./i/js/common/setSearch.js"></script>
<script type="text/javascript">
var oS = new Search(
  '<?= Tt::getPath() ?>',
  '<?= $d['name'] ?>',
  'json_<?= $d['actionKey'] ? $d['actionKey'] : $d['name'] ?>Search'
);
<? if ($s = $_REQUEST[$d['name'].'Search'] or $s = $_REQUEST['search'] or $s = $d['default']) { ?>
$('<?= $d['name'] ?>Mask').set('value', '<?= str_replace("'", "", $s) ?>');
oS._search();
<? } ?>
</script>
