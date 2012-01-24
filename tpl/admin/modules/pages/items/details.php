<?php

$isSorting = $d['page']['settings']['order'] == 'oid';

if ($d['canMove']) $widthPlus += 25;

if ($isSorting) $widthPlus += 29;

$allowEditSystemDates = Config::getVarVar('dd', 'allowEditSystemDates', true);
if ($allowEditSystemDates) $widthPlus += 25;

$pageMetaAllowed = AdminModule::isAllowed('pageMeta');
if ($pageMetaAllowed) $widthPlus += 25; // meta-tags icon

/* @var $oDdo Ddo */
$oDdo = $d['oDdo'];

?>
<div class="items">

<? if ($d['items']) { ?>
  <? //Tt::tpl('admin/common/pnums', $d) ?>
  <? unset($oDdo->fields['active']) ?>

<style>
#itemsTable .tools .clear {
width: <?= 140 + $widthPlus ?>px;
}
.items .loading .loader {
background-position: <?= 128 + $widthPlus ?>px 7px;
}
th.tools input {
margin-left: <?= 105 + $widthPlus ?>px;
}
</style>

<script type="text/javascript">
window.addEvent('domready', function(){
  Ngn.cp.ddItems = new Ngn.cp.DdItemsTable({
    <? if (!$isSorting) { ?>
    isSorting: false
    <? } ?>
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
});
</script>
  
<div class="moduleBody module_<?= $d['page']['module'] ?> layoutMode_<?= $d['page']['settings']['ddItemsLayout'] ?>">

<form method="post" id="itemsForm" method="post">
<table cellspacing="0" class="valign" id="itemsTable">
  <thead>
  <tr>
    <th class="tools"><input type="checkbox" id="checkAll" title="Выделить всё" class="tooltip" /></th>
    <? foreach ($oDdo->fields as $v) { ?>
      <th><?= $v['title'] ?></th>
    <? } ?>
  </tr>
  </thead>
  
  <tbody id="itemsTableBody">
  <? $n=0; foreach ($d['items'] as $v) { ?>
    <tr<?= $v['active'] ? '' : ' class="nonActive"'?> id="<?= 'item_'.$v['id'].'_'.$v['oid'] ?>">
      <td class="tools loader">
      <? if ($isSorting) { ?>
      <div class="dragBox tooltip" title="Схватить и перетащить"></div>
      <? } ?>
      <a class="iconBtn edit" title="<?= LANG_EDIT ?>"
        href="<?= Tt::getPath() ?>?a=edit&itemId=<?= $v['id'] ?>"><i></i></a>
      <? if ($pageMetaAllowed) { ?>
      <a class="iconBtn meta" title="Редактировать мета-теги"
        href="<?= Tt::getPath(1).'/pageMeta/'.$d['page']['id'].'/editItemMeta/'.$v['id'] ?>"><i></i></a>
      <? } ?>
      <? if ($allowEditSystemDates) { ?>  
      <a class="iconBtn editDate" title="Редактировать системные даты"
        href="<?= Tt::getPath(3) ?>/editItemSystemDates/<?= $v['id'] ?>"><i></i></a>
      <? } ?>
      <a class="iconBtn delete" title="<?= LANG_DELETE ?>"
        href="<?= Tt::getPath(4) ?>?a=delete&itemId=<?= $v['id'] ?>"><i></i></a>
      <a class="iconBtn <?= $v['active'] ? 'activate' : 'deactivate' ?>" title="<?= $v['active'] ? LANG_HIDE : LANG_SHOW ?>"
        href="<?= Tt::getPath(4) ?>?a=<?= $v['active'] ? 'deactivate' : 'activate' ?>&itemId=<?= $v['id'] ?>"><i></i></a>
      <a class="iconBtn link" target="_blank" title="<?= LANG_OPEN_ENTRY_PAGE_ON_SITE ?>"
        href="/<?= $v['pagePath'].'/'.$v['id'] ?>"><i></i></a>
      <? if ($d['canMove']) { ?>
      <a class="iconBtn move" title="<?= LANG_MOVE ?>"
        href="<?= Tt::getPath() ?>?a=moveForm&itemId=<?= $v['id'] ?>"><i></i></a>
      <? } ?>
      <input type="checkbox" name="itemIds[<?= $n ?>]" value="<?= $v['id'] ?>" />
      <div class="clear"><!-- --></div>
      </td>
      
    <!-- Начало цикла вывода полей -->
    <? foreach ($oDdo->fields as $f) { $a = $v[$f['name']]; ?>
      <td class="<?= 'n_'.$f['name'].' t_'.$f['type'] ?>">
        <?= $oDdo->el($a, $f['name'], $v['id']) ?>
      </td>
    <? } ?>
    <!-- Конец цикла вывода полей -->
    
    </tr>
  <? $n++; } ?>
  </tbody>
</table>
</form>
  <? //Tt::tpl('admin/common/pnums', $d) ?>
<? } else { ?>
  <p class="info"><i></i><?= LANG_NO_ENTRIES ?></p>
<? } ?>

</div>