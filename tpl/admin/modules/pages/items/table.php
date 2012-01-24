<div class="items">

<? if ($d['items']) { ?>
  <? if ($d['pagination']['pNums']) { ?>
    <div class="pNums pNumsTop">
      <?= $d['pagination']['pNums'] ?>
    </div>
  <? } ?>
<? unset($fields['active']) ?>

<?
/* @var $oF DdFields */
$oF = O::get('dd/DdFields', $d['page']['strName'], $d['page']['id']);
?>

<style>
#itemsTable th, #itemsTable td {
padding: 5px 15px 5px 0px;
font-size: 10px;
}
#itemsTable .loading .clear {
width: <?= !$d['canMove'] ? '140' : '165' ?>px;
}
#itemsTable .loader {
background-position: <?= !$d['canMove'] ? '128' : '147' ?>px 7px;
}
#itemsTable th, #itemsTable td {
width: 100px;
}
.tools input {
margin: 5px 0px 0px 5px;
}
th.tools input {
margin-left: <?= !$d['canMove'] ? '105' : '130' ?>px;
}
.tagsTree2 li {
white-space: nowrap;
}

</style>

<script type="text/javascript">
window.addEvent('domready', function(){
  new Ngn.cp.DdItemsTable($('itemsTable'), {
    isSorting: <?= $d['page']['settings']['order'] == 'oid' ? 'true' : 'false' ?>
  });
  new Ngn.cp.DdItemsGroup($('itemsTableBody'));

  $('itemsTableBody').getElements('a[class~=soundStat]').each(function(el) {
    el.addEvent('click', function() {
      new Ngn.Dialog.Alert({
        force: true,
        title: 'Статистика по прослушиванию трека',
        url: el.href.replace('soundStat', 'ajax_soundStat')
      });
      return false;
    });
  });

  new Lightbox({
    assetBaseUrl: './i/css/common',
    relString: 'ngnLightbox'
  }, $('itemsTableBody').getElements('a.lightbox'));
  
});
</script>
  
<div class="module_<?= $d['page']['controller'] ?>">

<form method="post" id="itemsForm" method="post">
<table cellspacing="0" class="valign" id="itemsTable">
  <thead>
  <tr>
    <th class="tools"><input type="checkbox" id="checkAll" title="Выделить всё" class="tooltip" /></th>
    <? foreach ($fields as $v) { ?>
      <th><?= $v['title'] ?></th>
    <? } ?>
  </tr>
  </thead>
  
  <tbody id="itemsTableBody">
  <? foreach ($d['items'] as $v) { ?>
    <tr<?= $v['active'] ? '' : ' class="nonActive"'?> id="<?= 'item_'.$v['id'].'_'.$v['oid'] ?>">
      <td class="tools loading">
      <a class="iconBtn edit" title="<?= LANG_EDIT ?>"
        href="<?= Tt::getPath(5) ?>?a=edit&itemId=<?= $v['id'] ?>"><i></i></a>
      <a class="iconBtn delete" title="<?= LANG_DELETE ?>"
        href="<?= Tt::getPath(5) ?>?a=delete&itemId=<?= $v['id'] ?>"><i></i></a>
      <a class="iconBtn <?= $v['active'] ? 'activate' : 'deactivate' ?>" title="<?= $v['active'] ? LANG_HIDE : LANG_SHOW ?>"
        href="<?= Tt::getPath(5) ?>?a=<?= $v['active'] ? 'deactivate' : 'activate' ?>&itemId=<?= $v['id'] ?>"><i></i></a>
      <a class="iconBtn link" target="_blank" title="<?= LANG_OPEN_ENTRY_PAGE_ON_SITE ?>"
        href="/<?= $v['pagePath'].'/'.$v['id'] ?>"><i></i></a>
      <? if ($d['canMove']) { ?>
      <a class="iconBtn move" title="<?= LANG_MOVE ?>"
        href="<?= Tt::getPath() ?>?a=moveForm&itemId=<?= $v['id'] ?>"><i></i></a>
      <? } ?>
      <input type="checkbox" name="itemIds[]" value="<?= $v['id'] ?>" />
      <div class="clear"><!-- --></div>
      </td>
      
    <!-- Начало цикла вывода полей -->
    <? foreach ($fields as $f) { $a = $v[$f['name']]; ?>
      <td class="<?= $f['name'] ?>">
        <?= $d['oDdo']->el($a, $f['name'], $v['id']) ?>
      </td>
    <? } ?>
    <!-- Конец цикла вывода полей -->
    
    </tr>
  <? } ?>
  </tbody>
</table>
</form>

  <? if ($d['pagination']['pNums']) { ?>
    <div class="pNums pNumsBottom">
      <?= $d['pagination']['pNums'] ?>
    </div>
  <? } ?>
<? } else { ?>
  <p class="info"><i></i><?= LANG_NO_ENTRIES ?></p>
<? } ?>

</div>